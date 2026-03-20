<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Banner slider
        $banners = Banner::where('position', 'header')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        // 2. Danh mục nổi bật
        $featuredCategories = Category::where('is_featured', 1)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        // 3. Sản phẩm nổi bật
        $featuredProducts = Product::where('status', 1)
            ->with(['variants' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderByDesc('is_featured')
            ->orderByDesc('sold_count')
            ->limit(12)
            ->get();

        // 4. Thương hiệu
        $brands = Brand::where('status', 1)
            ->orderBy('name')
            ->limit(10)
            ->get();

        // 5. Sản phẩm mới (FIX BUG OR WHERE)
        $newProducts = Product::where('status', 1)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhere('created_at', '>=', now()->subDays(7));
            })
            ->with(['variants' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('frontend.home', compact(
            'banners',
            'featuredCategories',
            'featuredProducts',
            'brands',
            'newProducts'
        ));
    }
}
