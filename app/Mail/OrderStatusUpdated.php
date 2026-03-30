<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public ?string $customMessage;
    public ?string $customSubject;

    public function __construct(Order $order, ?string $customMessage = null, ?string $customSubject = null)
    {
        $this->order = $order;
        $this->customMessage = $customMessage;
        $this->customSubject = $customSubject;
    }

    public function envelope(): Envelope
    {
        $subject = $this->customSubject ?: $this->subjectText();

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
            with: [
                'customMessage' => $this->customMessage,
            ],
        );
    }

    protected function subjectText(): string
    {
        return match ($this->order->status) {
            'pending' => 'Đơn hàng #' . $this->order->order_number . ' đang chờ xác nhận',
            'confirmed' => 'Đơn hàng #' . $this->order->order_number . ' đã được xác nhận',
            'shipping' => 'Đơn hàng #' . $this->order->order_number . ' đang giao',
            'completed' => 'Đơn hàng #' . $this->order->order_number . ' đã hoàn thành',
            'cancelled' => 'Đơn hàng #' . $this->order->order_number . ' đã bị hủy',
            'refunded' => 'Đơn hàng #' . $this->order->order_number . ' đã hoàn tiền',
            default => 'Cập nhật đơn hàng #' . $this->order->order_number,
        };
    }
}
