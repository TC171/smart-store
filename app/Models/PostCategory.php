<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    protected $fillable = [
        'name', 'slug', 'description', 'status', 'sort_order', 'meta_title', 'meta_description'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
