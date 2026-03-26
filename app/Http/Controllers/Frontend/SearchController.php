<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q');

        if (!$q) return [];

        $ascii = Str::ascii($q);

        $products = Product::query()
            ->with(['variants', 'category'])
            ->where('status', 1)
            ->where(function ($query) use ($q, $ascii) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$ascii}%")
                      ->orWhere('slug', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$ascii}%");
            })
            ->limit(6)
            ->get();

        // fallback tìm theo category
        if ($products->isEmpty()) {
            $products = Product::query()
                ->with(['variants', 'category'])
                ->where('status', 1)
                ->whereHas('category', function ($query) use ($q, $ascii) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('name', 'like', "%{$ascii}%");
                })
                ->limit(6)
                ->get();
        }

        return $products->map(function ($product) {

            $variantPrice = $product->variants->min('price');
            $basePrice = $product->sale_price 
                ? $product->sale_price 
                : $product->price;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $basePrice ?? $variantPrice ?? 0,
                'sale_price' => $product->sale_price ?: null,
                'original_price' => $product->price ?: null,
                'stock' => $product->stock ?? 0,
                'category' => $product->category?->name ?? null,
                'image' => $product->image 
                    ? asset($product->image) 
                    : asset('images/no-image.jpg'),
            ];
        });
    }
}