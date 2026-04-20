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
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
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

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending'         => 'Chờ duyệt',
            'approved_return' => 'Chờ gửi hàng',
            'refunded'        => 'Đã hoàn hàng',
            'rejected'        => 'Từ chối',
        ][$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute(): string
    {
        return [
            'refund' => 'Hoàn tiền',
            'return' => 'Hoàn hàng',
        ][$this->type] ?? $this->type;
    }
}
