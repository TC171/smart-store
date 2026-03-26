<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'banners' => $this->getBanners(),
            'featuredCategories' => $this->getFeaturedCategories(),
            'featuredProducts' => $this->getFeaturedProducts(),
            'brands' => $this->getBrands(),
            'coupons' => $this->getCoupons(),
            'newProducts' => $this->getNewProducts(),

            'phoneProducts' => $this->getProductsByCategorySlug('dien-thoai'),
            'laptopProducts' => $this->getProductsByCategorySlug('laptop'),
            'tabletProducts' => $this->getProductsByCategorySlug('may-tinh-bang'),
            'accessoryProducts' => $this->getProductsByCategorySlug('phu-kien'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BANNERS
    |--------------------------------------------------------------------------
    */
    protected function getBanners()
    {
        return Banner::where('position', 'header')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */
    protected function getFeaturedCategories()
    {
        return Category::where('is_featured', 1)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS - FEATURED
    |--------------------------------------------------------------------------
    */
    protected function getFeaturedProducts()
    {
        return Product::where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->withCount([
                'variants as total_stock' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(stock), 0)'))
            ])
            ->orderByDesc('is_featured')
            ->orderByDesc('sold_count')
            ->limit(12)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | NEW PRODUCTS
    |--------------------------------------------------------------------------
    */
    protected function getNewProducts()
    {
        return Product::where('status', 1)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhere('created_at', '>=', now()->subDays(7));
            })
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->limit(8)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS BY CATEGORY
    |--------------------------------------------------------------------------
    */
    protected function getProductsByCategorySlug($slug, $limit = 8)
    {
        return Product::where('status', 1)
            ->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug)->where('status', 1);
            })
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->withCount([
                'variants as total_stock' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(stock), 0)'))
            ])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | BRANDS
    |--------------------------------------------------------------------------
    */
    protected function getBrands()
    {
        return Brand::where('status', 1)
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COUPONS
    |--------------------------------------------------------------------------
    */
    protected function getCoupons()
    {
        return Coupon::where('status', 1)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH PAGE (IMPORTANT FOR HEADER)
    |--------------------------------------------------------------------------
    */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::where('status', 1)
            ->where('name', 'like', "%{$query}%")
            ->with([
                'category',
                'brand',
                'variants' => fn ($q) => $q->where('status', 1),
            ])
            ->paginate(12);

        return view('frontend.search', compact('products', 'query'));
    }
}