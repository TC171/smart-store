<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'shipper_id',
        'email',
        'coupon_id',
        'coupon_code',
        'total_amount',
        'subtotal',
        'shipping_fee',
        'shipping_cost',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_district',
        'shipping_postal_code',
        'shipping_country',
        'note',
        'cancel_reason',
        'ordered_at',
        'completed_at',
    ];

    protected $casts = [
        'ordered_at'  => 'datetime',
        'completed_at'=> 'datetime',
    ];

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    // --- Status Helpers ---

    public static function statusLabels(): array
    {
        return [
            'pending'         => 'Chờ xác nhận',
            'waiting_payment' => 'Chờ thanh toán',
            'confirmed'       => 'Đã xác nhận',
            'picked_up'       => 'Shipper đã nhận hàng',
            'shipping'        => 'Đang giao hàng',
            'failed_delivery' => 'Giao không thành công',
            'completed'       => 'Hoàn thành',
            'cancelled'       => 'Đã huỷ',
            'refunded'        => 'Đã hoàn hàng',
        ];
    }

    public static function statusColors(): array
    {
        return [
            'pending'         => 'bg-yellow-500/20 text-yellow-400',
            'waiting_payment' => 'bg-orange-500/20 text-orange-400',
            'confirmed'       => 'bg-blue-500/20 text-blue-400',
            'picked_up'       => 'bg-cyan-500/20 text-cyan-400',
            'shipping'        => 'bg-indigo-500/20 text-indigo-400',
            'failed_delivery' => 'bg-red-500/20 text-red-400',
            'completed'       => 'bg-green-500/20 text-green-400',
            'cancelled'       => 'bg-gray-500/20 text-gray-400',
            'refunded'        => 'bg-orange-500/20 text-orange-400',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::statusColors()[$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }

    public function formatPrice($field)
    {
        return number_format($this->$field, 0, ',', '.') . 'đ';
    }
}