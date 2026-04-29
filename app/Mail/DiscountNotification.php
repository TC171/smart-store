<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DiscountNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $coupon;

    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎁 Món quà đặc quyền dành riêng cho bạn từ Smart Store!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.discount-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
