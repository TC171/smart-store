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

    public function pickup(string $delivery)
    {
        $order = $this->findOrder($delivery);

        if ($order->status !== 'shipping') {
            return back()->with('error', 'Chỉ có thể xác nhận nhận hàng khi đơn đang ở trạng thái "Chờ nhận hàng".');
        }

        $order->update(['status' => 'picked_up']);

        return back()->with('success', '📦 Đã xác nhận nhận hàng từ kho. Tiến hành giao hàng!');
    }

    // ---------------------------------------------------------------
    // Bước 2: Kết quả giao hàng  →  picked_up → completed / failed_delivery
    // route: PATCH /deliveries/{delivery}/status
    // ---------------------------------------------------------------

    public function updateStatus(Request $request, string $delivery)
    {
        $order = $this->findOrder($delivery);

        $request->validate([
            'status' => 'required|in:completed,failed_delivery',
            'note'   => 'nullable|string|max:500',
        ]);

        if ($order->status !== 'picked_up') {
            return back()->with('error', 'Chỉ có thể cập nhật kết quả giao sau khi đã xác nhận nhận hàng từ kho.');
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'completed') {
            $updateData['payment_status'] = 'paid';
            $updateData['completed_at']   = now();
        }

        if ($request->filled('note')) {
            $existing = $order->note ? $order->note . "\n" : '';
            $updateData['note'] = $existing . '[Shipper] ' . $request->note;
        }

        $order->update($updateData);

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
            : '❌ Đã ghi nhận giao hàng thất bại. Admin sẽ xử lý tiếp.';

        return back()->with('success', $msg);
    }
}



//         $msg = $request->status === 'completed'
//             ? '✅ Giao hàng thành công! Đơn hàng đã được đánh dấu Đã thanh toán.'
//             : '❌ Đã ghi nhận giao hàng thất bại. Admin sẽ xử lý tiếp.';

//         return back()->with('success', $msg);
//     }
// }
