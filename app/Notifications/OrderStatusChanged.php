<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $orderNumber = $this->order->order_number ?? '#' . $this->order->id;
        $status = $this->order->status;

        $map = [
            'confirmed'  => ['icon' => '📦', 'color' => 'blue',   'title' => 'Đơn hàng đã được xác nhận',    'body' => "Đơn hàng {$orderNumber} đã được xác nhận và đang được chuẩn bị."],
            'shipping'   => ['icon' => '🚚', 'color' => 'orange', 'title' => 'Đơn hàng đang được giao',      'body' => "Đơn hàng {$orderNumber} đang trên đường đến tay bạn."],
            'completed'  => ['icon' => '✅', 'color' => 'green',  'title' => 'Đơn hàng đã hoàn thành',       'body' => "Đơn hàng {$orderNumber} đã hoàn thành. Cảm ơn bạn đã mua hàng! 🎉"],
            'cancelled'  => ['icon' => '❌', 'color' => 'red',    'title' => 'Đơn hàng đã bị hủy',           'body' => "Đơn hàng {$orderNumber} đã bị hủy."],
        ];

        $info = $map[$status] ?? [
            'icon'  => '🔔',
            'color' => 'gray',
            'title' => 'Cập nhật đơn hàng',
            'body'  => "Đơn hàng {$orderNumber} đã được cập nhật trạng thái.",
        ];

        return [
            'type'     => 'order_status',
            'icon'     => $info['icon'],
            'color'    => $info['color'],
            'title'    => $info['title'],
            'body'     => $info['body'],
            'url'      => route('customer.order.detail', $this->order->id),
            'order_id' => $this->order->id,
        ];
    }
}
