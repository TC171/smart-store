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
        $reviewedProductIds = auth('web')->user()->reviews()
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->toArray();

        return view('customer.order-detail', compact('order', 'reviewedProductIds'));
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

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $data['product_id'],
            ],
            [
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'],
                'is_approved' => false,
            ]
        );

        return back()->with('success', 'Đánh giá của bạn đã được gửi, admin sẽ xem xét và duyệt.');
    }
}

