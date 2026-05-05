<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /** Trang danh sách yêu thích */
    public function index()
    {
        $wishlists = Wishlist::with(['product.variants', 'product.images', 'product.category'])
            ->where('user_id', auth('web')->id())
            ->latest()
            ->paginate(12);

        return view('frontend.customer.wishlist', compact('wishlists'));
    }

    /** Toggle thêm / xóa sản phẩm khỏi wishlist (AJAX) */
    public function toggle(Request $request, Product $product)
    {
        $userId = auth('web')->id();
        if (!$userId) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $product->id]);
            $wishlisted = true;
        }

        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json([
            'wishlisted' => $wishlisted,
            'count'      => $count,
        ]);
    }

    /** Xóa 1 sản phẩm khỏi wishlist (form POST) */
    public function remove(Request $request, Product $product)
    {
        Wishlist::where('user_id', auth('web')->id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }
}
