<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Orders\UpdateOrderPaymentStatusRequest;
use App\Http\Requests\Admin\Orders\UpdateOrderStatusRequest;
use App\Models\InventoryHistory;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdated;
use App\Models\User;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::with('user', 'items');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->search) {
            $query->where('order_number', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                        ->orWhere('email', 'like', '%'.request('search').'%');
                });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items', 'items.variant']);

        


        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        // Nếu đơn hàng đã ở trạng thái cuối, chỉ cho phép cập nhật trạng thái thanh toán
        if (in_array($oldStatus, ['cancelled', 'refunded', 'failed_delivery'])) {
            if ($request->status !== $oldStatus) {
                return back()->with('error', 'Đơn hàng đã ở trạng thái cuối cùng, không thể thay đổi trạng thái đơn hàng nữa.');
            }
            
            // Nếu trạng thái đơn hàng không đổi, nhưng có yêu cầu đổi trạng thái thanh toán thì tiếp tục xử lý bên dưới
            if (!$request->filled('payment_status') || $request->payment_status === $oldPaymentStatus) {
                return back()->with('info', 'Không có thông tin nào được thay đổi.');
            }
        } else {
            // Thực thi quy trình nghiêm ngặt (Strict State Machine) cho các đơn hàng chưa ở trạng thái cuối
            $allowedTransitions = [
                'pending'         => ['confirmed'],
                'confirmed'       => ['shipping', 'cancelled'],
                'shipping'        => ['completed', 'failed_delivery'],
                'completed'       => ['refunded'],
            ];

            $allowedNext = $allowedTransitions[$oldStatus] ?? [];

            if ($request->status !== $oldStatus && !in_array($request->status, $allowedNext)) {
                $statusNames = [
                    'pending'         => 'Chờ xác nhận',
                    'confirmed'       => 'Đã xác nhận',
                    'shipping'        => 'Đang giao hàng',
                    'completed'       => 'Hoàn thành',
                    'failed_delivery' => 'Giao hàng không thành công',
                    'cancelled'       => 'Đã hủy',
                    'refunded'        => 'Đã hoàn hàng',
                ];
                $currentName = $statusNames[$oldStatus] ?? $oldStatus;
                return back()->with('error', "Từ trạng thái \"$currentName\" không được phép chuyển sang trạng thái đã chọn.");
            }
        }

        // Prevent backward payment status updates and restrict refunded status
        if ($request->filled('payment_status')) {
            if ($oldPaymentStatus === 'paid' && $request->payment_status === 'unpaid') {
                return back()->with('error', 'Không thể chuyển từ Đã thanh toán về Chưa thanh toán.');
            }
            if ($request->payment_status === 'refunded') {
                $isVNPay = ($order->payment_method === 'vnpay');
                $status = $order->status;

                $canRefund = false;
                if ($status === 'refunded') {
                    $canRefund = true; // Cả 2 đều được hoàn tiền khi "Đã hoàn hàng"
                } elseif ($isVNPay && in_array($status, ['failed_delivery', 'cancelled'])) {
                    $canRefund = true; // VNPay được hoàn tiền khi "Giao thất bại" hoặc "Đã hủy"
                }

                if (!$canRefund) {
                    if ($status === 'refunded') {
                        // Trường hợp này không xảy ra do logic trên, nhưng để cho chắc chắn
                    } elseif (in_array($status, ['failed_delivery', 'cancelled'])) {
                        return back()->with('error', 'Chỉ đơn hàng VNPay mới được phép chuyển sang "Đã hoàn tiền" khi Giao thất bại hoặc Đã hủy.');
                    } else {
                        return back()->with('error', 'Chỉ được cập nhật "Đã hoàn tiền" khi đơn hàng ở trạng thái Đã hoàn hàng (hoặc Giao thất bại/Đã hủy đối với VNPay).');
                    }
                }
                
                if ($oldPaymentStatus !== 'paid') {
                    return back()->with('error', 'Chỉ có thể hoàn tiền cho những đơn hàng đã thanh toán.');
                }
            }
            if ($oldPaymentStatus === 'refunded' && in_array($request->payment_status, ['unpaid', 'paid'])) {
                return back()->with('error', 'Đơn hàng đã hoàn tiền không thể chuyển lại trạng thái thanh toán khác.');
            }
        }

        $updateData = ['status' => $request->status];
        if ($request->filled('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        $order->update($updateData);

        if ($request->status !== $oldStatus) {
            try {
                $recipient = $order->email ?: ($order->user->email ?? null);
                if ($recipient) {
                    Mail::to($recipient)->send(new OrderStatusUpdated($order));
                }
            } catch (\Exception $e) {
                \Log::error("Lỗi gửi mail từ Admin: " . $e->getMessage());
            }
        }

        // Xử lý kho hàng và trạng thái thanh toán
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            // Đã hoàn thành -> Tự động đánh dấu đã thanh toán
            $order->update(['payment_status' => 'paid']);
        } elseif ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $previousStock = $variant->stock;
                    $variant->increment('stock', $item->quantity);

                    InventoryHistory::create([
                        'product_variant_id' => $variant->id,
                        'type' => 'return',
                        'quantity' => $item->quantity,
                        'previous_stock' => $previousStock,
                        'current_stock' => $variant->stock,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' => "Trả hàng từ đơn hàng #{$order->order_number}",
                        'user_id' => auth('admin')->id(),
                    ]);
                }
            }
        }

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function map(Order $order)
{
    $order->load('user');
    $shipper = $order->shipper_id ? \App\Models\User::find($order->shipper_id) : null;

    return view('admin.orders.map', compact('order', 'shipper'));
}

public function shipperLocation(Order $order)
{
    $shipper = $order->shipper_id ? \App\Models\User::find($order->shipper_id) : null;

    if (!$shipper || !$shipper->latitude) {
        return response()->json(['available' => false]);
    }

    return response()->json([
        'available'          => true,
        'latitude'           => $shipper->latitude,
        'longitude'          => $shipper->longitude,
        'name'               => $shipper->name,
        'location_updated_at' => $shipper->location_updated_at?->diffForHumans(),
    ]);
}




    public function updatePaymentStatus(UpdateOrderPaymentStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $oldPaymentStatus = $order->payment_status;

        // 🔥 Chặn cập nhật trạng thái "Đã thanh toán" thủ công cho đơn hàng COD khi chưa hoàn thành
        if ($order->payment_method === 'cod' && $request->payment_status === 'paid' && $order->status !== 'completed') {
            return back()->with('error', 'Đơn hàng COD chỉ có thể chuyển sang "Đã thanh toán" khi đơn hàng thực tế đã được giao thành công (Hoàn thành).');
        }

        if ($request->payment_status === 'refunded') {
            $isVNPay = ($order->payment_method === 'vnpay');
            $status = $order->status;

            $canRefund = false;
            if ($status === 'refunded') {
                $canRefund = true;
            } elseif ($isVNPay && in_array($status, ['failed_delivery', 'cancelled'])) {
                $canRefund = true;
            }

            if (!$canRefund) {
                return back()->with('error', 'Chỉ được cập nhật "Đã hoàn tiền" khi đơn hàng ở trạng thái Đã hoàn hàng (hoặc Giao thất bại/Đã hủy đối với VNPay).');
            }

            if ($oldPaymentStatus !== 'paid') {
                return back()->with('error', 'Chỉ có thể hoàn tiền cho những đơn hàng đã thanh toán.');
            }
        }

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Cập nhật trạng thái thanh toán thành công');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Xóa đơn hàng thành công');
    }

    public function assignShipper(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        // ✅ FIX: validate theo bảng shippers
        $request->validate([
            'shipper_id' => 'required|exists:shippers,id'
        ]);

        // ✅ FIX: dùng Shipper model riêng
        $shipper = Shipper::where('id', $request->shipper_id)
            ->where('status', 'active')
            ->first();

        if (!$shipper) {
            return back()->with('error', 'Shipper không hợp lệ');
        }

        $order->shipper_id = $shipper->id;
        $order->save();

        return back()->with('success', 'Gán shipper thành công');
    }
}