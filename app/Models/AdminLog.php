<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    /**
     * Tắt timestamp tự động do bảng chỉ có created_at
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'admin_id',
        'action',
        'model',
        'model_id',
        'description',
        'ip_address',
        'created_at',
    ];

    /**
     * Mối quan hệ với bảng Users (Admin)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
