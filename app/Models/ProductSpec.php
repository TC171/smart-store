<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    protected $table = 'product_specs';

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'sort_order',
    ];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}