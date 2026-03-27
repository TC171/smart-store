<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'coupon_id',
        'coupon_code', // Bổ sung thêm cột này vì Controller của bạn đang dùng
        'total_amount',
        'subtotal',
        'shipping_fee',
        'shipping_cost',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'status',
        'payment_status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_district',
        'shipping_postal_code',
        'shipping_country',
        'note',
        'ordered_at',
        'completed_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // --- Helper Methods (Tiện ích bổ sung) ---

    /**
     * Lấy màu sắc tương ứng với trạng thái đơn hàng (Dành cho view màu Cam)
     */
    public function getStatusColorAttribute()
    {
        return [
            'pending'   => 'orange',   // Chờ xử lý cho màu cam luôn
            'confirmed' => 'blue',
            'shipping'  => 'indigo',
            'completed' => 'green',
            'cancelled' => 'gray',
        ][$this->status] ?? 'gray';
    }

    /**
     * Định dạng tiền tệ VND (Để view gọi cho gọn)
     */
    public function formatPrice($field)
    {
        return number_format($this->$field, 0, ',', '.') . 'đ';
    }
}