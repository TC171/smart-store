<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = auth('web')->user()->orders()->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.variant.product']);

        $productIds = $order->items->pluck('product_id')->filter()->unique();

        $reviewCounts = auth('web')->user()->reviews()
            ->whereIn('product_id', $productIds)
            ->selectRaw('product_id, count(*) as total')
            ->groupBy('product_id')
            ->pluck('total', 'product_id')
            ->toArray();

        $completedOrderCounts = [];
        $userId = auth('web')->id();
        foreach ($productIds as $productId) {
            $completedOrderCounts[$productId] = Order::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereHas('items', fn($q) => $q->where('product_id', $productId))
                ->count();
        }

        return view('customer.order-detail', compact('order', 'reviewCounts', 'completedOrderCounts'));
    }

    public function storeReview(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'completed') {
            return back()->with('error', 'Chỉ có đơn hàng hoàn thành mới được đánh giá.');
        }

        $order->load('items');
        $productIds = $order->items->pluck('product_id')->filter()->unique()->values()->all();

        $data = $request->validate([
            'product_id' => ['required', 'in:' . implode(',', $productIds)],
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string'],
        ]);

        $userId = auth()->id();
        $productId = $data['product_id'];

        $currentReviewCount = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->count();

        $completedOrderCount = Order::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->count();

        if ($currentReviewCount >= $completedOrderCount) {
            return back()->with('error', 'Bạn đã đánh giá đủ số lần cho sản phẩm này.');
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'],
            'is_approved' => false,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi, admin sẽ xem xét và duyệt.');
    }
}

