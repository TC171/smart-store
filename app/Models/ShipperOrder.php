<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipperOrder extends Model
{
    protected $fillable = [
        'order_id',
        'shipper_id',
        'status',
        'note',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'assigned'   => 'Đã phân công',
            'picked_up'  => 'Đã lấy hàng',
            'delivering' => 'Đang giao',
            'delivered'  => 'Giao thành công',
            'failed'     => 'Giao thất bại',
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'assigned'   => 'bg-yellow-500/20 text-yellow-400',
            'picked_up'  => 'bg-blue-500/20 text-blue-400',
            'delivering' => 'bg-indigo-500/20 text-indigo-400',
            'delivered'  => 'bg-green-500/20 text-green-400',
            'failed'     => 'bg-red-500/20 text-red-400',
        ][$this->status] ?? 'bg-gray-500/20 text-gray-400';
    }
}

//    public function getStatusColorAttribute(): string
//     {
//         return [
//             'assigned'   => 'bg-yellow-500/20 text-yellow-400',
//             'picked_up'  => 'bg-blue-500/20 text-blue-400',
//             'delivering' => 'bg-indigo-500/20 text-indigo-400',
//             'delivered'  => 'bg-green-500/20 text-green-400',
//             'failed'     => 'bg-red-500/20 text-red-400',
//         ][$this->status] ?? 'bg-gray-500/20 text-gray-400';
//     }
// }