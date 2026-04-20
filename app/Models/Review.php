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
        'images',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Lấy danh sách đường dẫn ảnh đầy đủ
     */
    public function getImageUrls()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function($path) {
            return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
        }, $this->images);
    }

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