<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function products($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ])
            ->paginate(20);

        return view('frontend.category.products', compact('category', 'products'));
    }
}
