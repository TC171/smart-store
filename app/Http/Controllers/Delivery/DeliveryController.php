<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class DeliveryController extends Controller
{
    protected $deliveryStatuses = ['assigned', 'picked_up', 'delivering', 'delivered', 'failed', 'returned'];

    // Mapping delivery_status → trạng thái đơn hàng (status) để cập nhật cho admin & customer
    protected $statusSyncMap = [
        'picked_up'  => 'shipping',    // Vẫn đang shipping
        'delivering' => 'shipping',    // Vẫn đang shipping
        'delivered'  => 'completed',   // ✅ Hoàn thành
        'failed'     => 'shipping',    // Chưa giao được, còn trong luồng
        'returned'   => 'cancelled',   // Trả về → coi như hủy
    ];

    public function dashboard()
    {
        $userId = auth('delivery')->id(); // ✅ FIX: dùng guard 'delivery'

        $stats = Order::where('delivery_user_id', $userId)
            ->selectRaw('delivery_status, COUNT(*) as count')
            ->groupBy('delivery_status')
            ->pluck('count', 'delivery_status')
            ->toArray();

        $recentOrders = Order::where('delivery_user_id', $userId)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('delivery.dashboard', compact('stats', 'recentOrders'));
    }

    public function index(Request $request)
    {
        $userId = auth('delivery')->id(); // ✅ FIX: dùng guard 'delivery'
        $status = $request->status ?? 'assigned';

        $orders = Order::where('delivery_user_id', $userId)
            ->when($status, function ($q) use ($status) {
                if (in_array($status, $this->deliveryStatuses, true)) {
                    $q->where('delivery_status', $status);
                } else {
                    $q->where('status', $status);
                }
            })
            ->latest()
            ->get();

        return view('delivery.orders.index', compact('orders', 'status'));
    }

    public function pickup(Order $order)
    {
        $this->checkOwner($order);

        $this->updateDeliveryStatus($order, 'picked_up');

        return back()->with('success', '📦 Đã nhận đơn hàng từ kho. Tiến hành giao hàng!');
    }

    public function delivering(Order $order)
    {
        $this->checkOwner($order);

        $this->updateDeliveryStatus($order, 'delivering');

        return back()->with('success', '🚚 Đã bắt đầu giao hàng!');
    }

    public function done(Order $order)
    {
        $this->checkOwner($order);

        $this->updateDeliveryStatus($order, 'delivered');

        return back()->with('success', '✅ Giao hàng thành công!');
    }

    public function fail(Order $order)
    {
        $this->checkOwner($order);

        $this->updateDeliveryStatus($order, 'failed');

        return back()->with('error', '❌ Đã đánh dấu giao hàng thất bại.');
    }

    public function returned(Order $order)
    {
        $this->checkOwner($order);

        $this->updateDeliveryStatus($order, 'returned');

        return back()->with('warning', '↩️ Đã đánh dấu đơn hàng trả về kho.');
    }

    public function show(Order $order)
    {
        $this->checkOwner($order);

        return view('delivery.orders.show', compact('order'));
    }

    // =========================================================
    // CORE: Cập nhật trạng thái + đồng bộ admin + gửi mail
    // =========================================================

    /**
     * Cập nhật delivery_status, đồng bộ order.status cho admin,
     * và gửi email thông báo cho customer.
     */
    private function updateDeliveryStatus(Order $order, string $deliveryStatus): void
    {
        $updateData = [
            'delivery_status' => $deliveryStatus,
        ];

        // Đồng bộ order.status để admin & customer thấy trạng thái cập nhật
        if (isset($this->statusSyncMap[$deliveryStatus])) {
            $updateData['status'] = $this->statusSyncMap[$deliveryStatus];
        }

        $order->update($updateData);

        // Gửi email thông báo cho customer
        $this->sendDeliveryNotification($order, $deliveryStatus);

        \Log::info('DeliveryStatus updated', [
            'order_id'          => $order->id,
            'delivery_status'   => $deliveryStatus,
            'order_status'      => $updateData['status'] ?? '(không đổi)',
            'shipper_id'        => auth('delivery')->id(),
        ]);
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    /**
     * ✅ FIX: Dùng guard 'delivery' thay vì guard mặc định 'web'
     */
    private function checkOwner(Order $order): void
    {
        if ($order->delivery_user_id !== auth('delivery')->id()) {
            abort(403, 'Bạn không có quyền thao tác đơn hàng này.');
        }
    }

    private function sendDeliveryNotification(Order $order, string $deliveryStatus): void
    {
        $labels = [
            'assigned'   => 'Đơn hàng của bạn đã được gán cho nhân viên giao hàng',
            'picked_up'  => 'Nhân viên giao hàng đã nhận hàng từ kho',
            'delivering' => 'Đơn hàng đang trên đường giao tới bạn',
            'delivered'  => 'Đơn hàng đã được giao thành công',
            'failed'     => 'Giao hàng thất bại, vui lòng liên hệ shop để được hỗ trợ',
            'returned'   => 'Đơn hàng đã bị trả về kho, vui lòng liên hệ shop',
        ];

        try {
            $recipient = $order->email ?: ($order->user->email ?? null);
            if ($recipient) {
                $message = $labels[$deliveryStatus] ?? 'Trạng thái giao hàng đã được cập nhật';
                $subject = 'Cập nhật đơn hàng #' . $order->order_number . ' — ' . $message;

                Mail::to($recipient)->send(new OrderStatusUpdated($order, $message, $subject));
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi mail thông báo giao hàng: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
        }
    }
}