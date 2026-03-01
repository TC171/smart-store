<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        // FILTER CATEGORY
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        // FILTER BRAND
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // FILTER STATUS
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // SORT GIÁ (dùng subquery)
        if ($request->sort_price) {

            $query->orderBy(
                DB::raw("(SELECT COALESCE(sale_price, price)
                     FROM product_variants
                     WHERE product_variants.product_id = products.id
                     ORDER BY id ASC
                     LIMIT 1)"),
                $request->sort_price
            );
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);

        if ($request->ajax()) {
            $categories = Category::all();
            $brands = Brand::all();

            return view('admin.products.partials.table', compact(
                'products',
                'categories',
                'brands'
            ))->render();
        }

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'status' => 'active'
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted');
    }
}
