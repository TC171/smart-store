<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function products(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $query = Product::where('status', 1)
            ->where('category_id', $category->id)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ]);

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('price_range') && $request->price_range !== 'all') {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('status', 1);

                switch ($request->price_range) {
                    case 'under_2m':
                        $q->whereRaw('COALESCE(sale_price, price) < ?', [2000000]);
                        break;
                    case '2m_4m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [2000000, 4000000]);
                        break;
                    case '4m_7m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [4000000, 7000000]);
                        break;
                    case '7m_13m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [7000000, 13000000]);
                        break;
                    case '13m_20m':
                        $q->whereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [13000000, 20000000]);
                        break;
                    case 'over_20m':
                        $q->whereRaw('COALESCE(sale_price, price) > ?', [20000000]);
                        break;
                }
            });
        }

        switch ($request->get('sort')) {
            case 'price_asc':
                $query->withMin(['variants as min_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                    ->orderBy('min_variant_price');
                break;

            case 'price_desc':
                $query->withMax(['variants as max_variant_price' => fn($q) => $q->where('status', 1)], 'price')
                    ->orderByDesc('max_variant_price');
                break;

            case 'best_seller':
                $query->orderByDesc('sold_count')->latest();
                break;

            case 'newest':
                $query->latest();
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(40)->withQueryString();

        $categories = Category::where('status', 1)->orderBy('name')->get();

        $brands = Brand::whereHas('products', function ($q) use ($category) {
            $q->where('status', 1)
              ->where('category_id', $category->id);
        })->orderBy('name')->get();

        return view('frontend.products.featured', compact(
            'products',
            'categories',
            'brands',
            'category'
        ));
    }
}