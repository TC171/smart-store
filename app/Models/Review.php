<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // 👤 User đánh giá
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 📦 Sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}