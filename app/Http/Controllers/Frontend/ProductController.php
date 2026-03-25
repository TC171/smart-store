<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'variants'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $this->authorize('view', $product);

        return view('frontend.products.show', compact('product'));
    }

    public function featured()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::where('is_featured', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ])
            ->paginate(20);

        return view('frontend.products.featured', compact('products'));
    }
}
