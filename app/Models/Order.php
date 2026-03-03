<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number','user_id','coupon_id',
        'total_amount','shipping_fee','discount_amount','tax_amount','grand_total',
        'status','payment_status',
        'shipping_name','shipping_phone','shipping_address','shipping_city','shipping_district','shipping_country',
        'note','ordered_at','completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}