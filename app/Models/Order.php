<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'email',
        'coupon_id',
        'coupon_code', // Bổ sung thêm cột này vì Controller của bạn đang dùng
            'delivery_user_id', // 🚚 thêm dòng này
            'delivery_status',      // ✅ FIX: thêm vào fillable để shipper cập nhật được
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
        public function deliveryStaff()
        {
            return $this->belongsTo(User::class, 'delivery_user_id');
        }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    
    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'order_id');
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
     * Label tiếng Việt cho delivery_status (dùng trong view admin & customer)
     */
    public function getDeliveryStatusLabelAttribute(): string
    {
        return [
            'assigned'   => 'Đã gán shipper',
            'picked_up'  => 'Shipper đã nhận hàng',
            'delivering' => 'Đang giao hàng',
            'delivered'  => 'Giao thành công',
            'failed'     => 'Giao thất bại',
            'returned'   => 'Đã trả về kho',
        ][$this->delivery_status ?? ''] ?? '—';
    }
 
    /**
     * Badge color cho delivery_status
     */
    public function getDeliveryStatusColorAttribute(): string
    {
        return [
            'assigned'   => 'info',
            'picked_up'  => 'primary',
            'delivering' => 'warning',
            'delivered'  => 'success',
            'failed'     => 'danger',
            'returned'   => 'secondary',
        ][$this->delivery_status ?? ''] ?? 'secondary';
    }

    /**
     * Định dạng tiền tệ VND (Để view gọi cho gọn)
     */
    public function formatPrice($field)
    {
        return number_format($this->$field, 0, ',', '.') . 'đ';
    }
}