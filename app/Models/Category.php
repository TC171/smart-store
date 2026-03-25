<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'parent_id',
        'is_featured',
        'status',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /*
    |------------------------------------------------------------------
    | RELATIONSHIPS
    |------------------------------------------------------------------
    */

    // 1 Category có nhiều Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Category cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Category con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
