<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'type',
        'reason',
        'video_path',
        'video_original_name',
        'status',
        'return_code',
        'admin_note',
        'reviewed_at',
        'reviewed_by',
        'return_shipper_id',
        'picked_up_at',
        'returned_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'returned_at'  => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Shipper được chỉ định đi lấy hàng hoàn về shop.
     */
    public function returnShipper()
    {
        return $this->belongsTo(User::class, 'return_shipper_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending'           => 'Chờ duyệt',
            'approved_return'   => 'Chờ shipper lấy hàng',
            'shipper_picking'   => 'Shipper đang lấy hàng',
            'shipper_returning' => 'Đang chuyển hàng về shop',
            'goods_received'    => 'Hàng đã về shop',
            'refunded'          => 'Đã hoàn tiền',
            'rejected'          => 'Từ chối',
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'pending'           => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/40',
            'approved_return'   => 'bg-blue-500/20 text-blue-400 border-blue-500/40',
            'shipper_picking'   => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/40',
            'shipper_returning' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/40',
            'goods_received'    => 'bg-orange-500/20 text-orange-400 border-orange-500/40',
            'refunded'          => 'bg-green-500/20 text-green-400 border-green-500/40',
            'rejected'          => 'bg-red-500/20 text-red-400 border-red-500/40',
        ][$this->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/40';
    }

    public function getStatusIconAttribute(): string
    {
        return [
            'pending'           => '⏳',
            'approved_return'   => '📋',
            'shipper_picking'   => '🚚',
            'shipper_returning' => '🔄',
            'goods_received'    => '📦',
            'refunded'          => '✅',
            'rejected'          => '❌',
        ][$this->status] ?? '❓';
    }

    public function getTypeLabelAttribute(): string
    {
        return [
            'refund' => 'Hoàn tiền',
            'return' => 'Hoàn hàng',
        ][$this->type] ?? $this->type;
    }
}