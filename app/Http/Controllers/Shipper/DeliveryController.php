<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected function shipperId(): int
    {
        return auth('shipper')->id();
    }

    /**
     * Lấy đơn thuộc shipper hiện tại, abort 404 nếu không tìm thấy.
     * Nhận $id trực tiếp từ route parameter (tên {delivery}).
     */
    protected function findOrder(string $id): Order
    {
        return Order::where('id', $id)
            ->where('shipper_id', $this->shipperId())
            ->firstOrFail();
    }

    // ---------------------------------------------------------------
    // Dashboard
    // ---------------------------------------------------------------

    public function dashboard()
    {
        $sid = $this->shipperId();

        $stats = [
            'shipping'        => Order::where('shipper_id', $sid)->where('status', 'shipping')->count(),
            'picked_up'       => Order::where('shipper_id', $sid)->where('status', 'picked_up')->count(),
            'completed'       => Order::where('shipper_id', $sid)->where('status', 'completed')->count(),
            'failed_delivery' => Order::where('shipper_id', $sid)->where('status', 'failed_delivery')->count(),
            'total'           => Order::where('shipper_id', $sid)->count(),
        ];

        $activeOrders = Order::where('shipper_id', $sid)
            ->whereIn('status', ['shipping', 'picked_up'])
            ->latest()
            ->take(5)
            ->get();

        return view('shipper.dashboard', compact('stats', 'activeOrders'));
    }

    // ---------------------------------------------------------------
    // Danh sách đơn
    // ---------------------------------------------------------------

    public function index(Request $request)
    {
        $query = Order::where('shipper_id', $this->shipperId());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('shipper.deliveries.index', compact('orders'));
    }

    // ---------------------------------------------------------------
    // Chi tiết  —  route: GET /deliveries/{delivery}
    // ---------------------------------------------------------------

    public function show(string $delivery)
    {
        $order = $this->findOrder($delivery);
        $order->load('items.variant');

        return view('shipper.deliveries.show', compact('order'));
    }

    // ---------------------------------------------------------------
    // Bước 1: Nhận hàng từ kho  →  shipping → picked_up
    // route: PATCH /deliveries/{delivery}/pickup
    // ---------------------------------------------------------------
// app/Http/Controllers/Shipper/DeliveryController.php

public function pickup($id) 
    {
        // Sử dụng auth('shipper') để đồng nhất với guard bạn đang dùng
        $order = Order::where('id', $id)->where('shipper_id', auth('shipper')->id())->firstOrFail();
        
        // Chỉ cho phép đơn hàng ở trạng thái 'confirmed' (Admin đã xác nhận) được bắt đầu đi giao
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Đơn hàng này không ở trạng thái chờ giao!');
        }

        $order->update([
            'status' => 'shipping', // Nhảy thẳng sang đang giao
            'picked_up_at' => now(), 
        ]);

        return redirect()->back()->with('success', '✅ Đã xác nhận! Đơn hàng đang trong quá trình giao tới khách.');
    }

    // ---------------------------------------------------------------
    // Bước 2: Cập nhật kết quả: Thành công hoặc Thất bại
    // ---------------------------------------------------------------
    public function updateStatus(Request $request, string $delivery)
    {
        $order = $this->findOrder($delivery);

        $request->validate([
            'status' => 'required|in:completed,failed_delivery',
            'note'   => 'nullable|string|max:500',
        ]);

        // Ngọc chú ý: Đổi điều kiện kiểm tra từ 'picked_up' sang 'shipping'
        if ($order->status !== 'shipping') {
            return back()->with('error', 'Đơn hàng phải ở trạng thái "Đang giao" mới có thể cập nhật kết quả.');
        }

        $updateData = ['status' => $request->status];

        // Xử lý khi Giao thành công
        if ($request->status === 'completed') {
            $updateData['payment_status'] = 'paid';
            $updateData['completed_at']   = now();
        }

        // Lưu ghi chú của Shipper (Đặc biệt quan trọng khi giao thất bại để lưu lý do)
        if ($request->filled('note')) {
            $existing = $order->note ? $order->note . "\n" : '';
            $updateData['note'] = $existing . '[Shipper] ' . $request->note;
        }

        $order->update($updateData);

        // Gửi mail nếu giao thành công
        if ($request->status === 'completed') {
            try {
                $recipient = $order->email ?: ($order->user?->email ?? null);
                if ($recipient) {
                    \Mail::to($recipient)->send(new \App\Mail\OrderDelivered($order));
                }
            } catch (\Exception $e) {
                \Log::error('Lỗi gửi mail OrderDelivered: ' . $e->getMessage());
            }
        }

        $msg = $request->status === 'completed'
            ? '✅ Giao hàng thành công! Đơn hàng đã được đánh dấu Đã thanh toán.'
            : '❌ Đã ghi nhận giao hàng thất bại. Đơn hàng sẽ được chuyển về danh sách xử lý.';

        return back()->with('success', $msg);
    }
}