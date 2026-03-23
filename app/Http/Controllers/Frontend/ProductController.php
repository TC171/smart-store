<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Lấy product theo slug
        $product = Product::where('slug', $slug)
            ->with([
                'variants',
                'images',
                'brand',
                'category'
            ])
            ->firstOrFail();

        return view('frontend.products.show', compact(
            'product'
        ));
    }
}