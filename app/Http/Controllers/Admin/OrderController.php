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
use App\Models\Shipper; // ✅ THÊM MỚI

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

        // ✅ FIX: dùng Shipper model riêng
        $shippers = Shipper::where('status', 'active')->get();

        return view('admin.orders.show', compact('order', 'shippers'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

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

        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            // completed logic
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

    public function updatePaymentStatus(UpdateOrderPaymentStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

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