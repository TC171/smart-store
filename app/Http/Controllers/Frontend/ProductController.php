<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $this->authorize('view', $product);

        return view('frontend.products.show', compact('product'));
    }

    public function featured(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::where('status', 1)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('status', 1);
                },
            ]);

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Lọc theo khoảng giá
        if ($request->filled('price_range') && $request->price_range !== 'all') {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('status', 1);

                switch ($request->price_range) {
                    case 'under_2m':
                        $q->whereRaw('COALESCE(sale_price, price) < ?', [2000000]);
                        break;

                    case '2m_4m':
                        $q->whereRaw('COALESCE(sale_price, price) >= ? AND COALESCE(sale_price, price) <= ?', [2000000, 4000000]);
                        break;

                    case '4m_7m':
                        $q->whereRaw('COALESCE(sale_price, price) >= ? AND COALESCE(sale_price, price) <= ?', [4000000, 7000000]);
                        break;

                    case '7m_13m':
                        $q->whereRaw('COALESCE(sale_price, price) >= ? AND COALESCE(sale_price, price) <= ?', [7000000, 13000000]);
                        break;

                    case '13m_20m':
                        $q->whereRaw('COALESCE(sale_price, price) >= ? AND COALESCE(sale_price, price) <= ?', [13000000, 20000000]);
                        break;

                    case 'over_20m':
                        $q->whereRaw('COALESCE(sale_price, price) > ?', [20000000]);
                        break;
                }
            });
        }

        // Sắp xếp
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->withMin([
                    'variants as min_variant_price' => function ($q) {
                        $q->where('status', 1);
                    }
                ], 'price')->orderBy('min_variant_price', 'asc');
                break;

            case 'price_desc':
                $query->withMax([
                    'variants as max_variant_price' => function ($q) {
                        $q->where('status', 1);
                    }
                ], 'price')->orderBy('max_variant_price', 'desc');
                break;

            case 'best_seller':
                $query->orderByDesc('sold_count')->latest();
                break;

            case 'newest':
                $query->latest();
                break;

            default:
                $query->orderByDesc('is_featured')
                      ->orderByDesc('sold_count')
                      ->latest();
                break;
        }

        $products = $query->paginate(40)->withQueryString();

        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->get();

        $brands = Brand::whereHas('products', function ($q) {
                $q->where('status', 1);
            })
            ->orderBy('name')
            ->get();

        return view('frontend.products.featured', compact(
            'products',
            'categories',
            'brands'
        ));
    }
}