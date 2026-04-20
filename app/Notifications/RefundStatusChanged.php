<?php

namespace App\Notifications;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefundStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public RefundRequest $refund) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $order = $this->refund->order;
        $orderNumber = $order?->order_number ?? '#' . $order?->id;

        $status = $this->refund->status;

        if ($status === 'approved_return') {
            return [
                'type'       => 'refund_approved',
                'icon'       => '✅',
                'color'      => 'green',
                'title'      => 'Yêu cầu hoàn hàng đã được duyệt',
                'body'       => "Yêu cầu hoàn hàng cho đơn {$orderNumber} đã được chấp nhận. Mã trả hàng: {$this->refund->return_code}",
                'url'        => route('customer.order.detail', $order?->id),
                'order_id'   => $order?->id,
                'refund_id'  => $this->refund->id,
            ];
        }

        if ($status === 'refunded') {
            return [
                'type'       => 'refund_completed',
                'icon'       => '💰',
                'color'      => 'blue',
                'title'      => 'Đã hoàn hàng thành công',
                'body'       => "Đơn hàng {$orderNumber} của bạn đã được hoàn hàng thành công.",
                'url'        => route('customer.order.detail', $order?->id),
                'order_id'   => $order?->id,
                'refund_id'  => $this->refund->id,
            ];
        }

        if ($status === 'rejected') {
            return [
                'type'       => 'refund_rejected',
                'icon'       => '❌',
                'color'      => 'red',
                'title'      => 'Yêu cầu hoàn hàng bị từ chối',
                'body'       => "Yêu cầu hoàn hàng của đơn {$orderNumber} đã bị từ chối. Lý do: " . ($this->refund->admin_note ?? 'Không rõ'),
                'url'        => route('customer.order.detail', $order?->id),
                'order_id'   => $order?->id,
                'refund_id'  => $this->refund->id,
            ];
        }

        return [
            'type'      => 'refund_update',
            'icon'      => '🔄',
            'color'     => 'gray',
            'title'     => 'Cập nhật yêu cầu hoàn hàng',
            'body'      => "Yêu cầu hoàn hàng cho đơn {$orderNumber} đã được cập nhật.",
            'url'       => route('customer.order.detail', $order?->id),
            'order_id'  => $order?->id,
            'refund_id' => $this->refund->id,
        ];
    }
}
