<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show($slug)
    {
        // Lấy category
        $category = Category::where('slug', $slug)
            ->firstOrFail();

        // Lấy category con
        $categoryIds = Category::where('parent_id', $category->id)
            ->pluck('id')
            ->toArray();

        $categoryIds[] = $category->id;

        // Lấy product
        $products = Product::whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->with(['variants'])
            ->latest()
            ->paginate(12);

        return view('frontend.products.index', compact(
            'category',
            'products'
        ));
    }
}