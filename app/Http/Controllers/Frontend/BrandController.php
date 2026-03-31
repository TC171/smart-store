<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;

class BrandController extends Controller
{
    public function products($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ])
            ->paginate(20);

        return view('frontend.brand.products', compact('brand', 'products'));
    }
}
