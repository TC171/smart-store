<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = auth('web')->user()
            ->orders()
            ->with(['items', 'refundRequests'])
            ->latest()
            ->paginate(10);

        // Dùng view đang hoạt động (customer.orders có refund button)
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.variant.product', 'refundRequests']);

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
public function cancel(Request $request, Order $order)
{
    $this->authorize('view', $order);

    if (in_array($order->status, ['shipping', 'completed', 'failed_delivery', 'refunded', 'cancelled'])) {
        return back()->with('error', 'Đơn hàng đã được vận chuyển hoặc xử lý, không thể hủy.');
    }

   $cancelReason = trim($request->cancel_reason ?? '');

$paymentStatus = ($order->payment_method === 'vnpay' && $order->payment_status === 'paid')
    ? 'refunded'
    : $order->payment_status;

$order->update([
    'status'              => 'cancelled',
    'cancellation_reason' => $cancelReason ?: null,
    'payment_status'      => $paymentStatus,
]);


    return back()->with('success', 'Đơn hàng #' . $order->order_number . ' đã được hủy thành công.');
}


    public function track(Order $order)
{
    $this->authorize('view', $order);

    if (!in_array($order->status, ['picked_up', 'shipping'])) {
        return redirect()->route('customer.order.detail', $order)
            ->with('error', 'Chỉ có thể theo dõi đơn hàng đang được giao.');
    }

    $shipper = $order->shipper_id ? \App\Models\User::find($order->shipper_id) : null;

    return view('customer.orders.track', compact('order', 'shipper'));
}

public function trackData(Order $order)
{
    $this->authorize('view', $order);

    $shipper = $order->shipper_id ? \App\Models\User::find($order->shipper_id) : null;

    if (!$shipper || !$shipper->latitude) {
        return response()->json(['available' => false]);
    }

    return response()->json([
        'available'           => true,
        'latitude'            => $shipper->latitude,
        'longitude'           => $shipper->longitude,
        'location_updated_at' => $shipper->location_updated_at?->diffForHumans(),
    ]);
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
            'rating'     => ['required', 'integer', 'between:1,5'],
            'title'      => ['nullable', 'string', 'max:255'],
            'comment'    => ['required', 'string'],
            'images'     => ['nullable', 'array', 'max:5'],
            'images.*'   => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $userId    = auth()->id();
        $productId = $data['product_id'];

        $currentReviewCount = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->count();

        // Lấy số lượng đơn hàng thành công và KHÔNG có yêu cầu hoàn trả (hoặc yêu cầu bị từ chối)
        $validOrderCount = Order::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->whereDoesntHave('refundRequests', function($q) {
                $q->whereIn('status', ['pending', 'approved_return', 'refunded']);
            })
            ->count();

        if ($validOrderCount == 0) {
            // Kiểm tra xem có đơn hàng nào bị chặn do đang hoàn tiền không
            $hasRefundRequest = Order::where('user_id', $userId)
                ->whereHas('items', fn($q) => $q->where('product_id', $productId))
                ->whereHas('refundRequests', function($q) {
                    $q->whereIn('status', ['pending', 'approved_return', 'refunded']);
                })
                ->exists();

            if ($hasRefundRequest) {
                return back()->with('error', 'Sản phẩm đang trong quá trình hoàn hàng/hoàn tiền nên không thể đánh giá.');
            }
            
            return back()->with('error', 'Bạn cần mua sản phẩm này mới có thể đánh giá.');
        }

        if ($currentReviewCount >= $validOrderCount) {
            return back()->with('error', 'Bạn đã đánh giá đủ số lần cho sản phẩm này.');
        }

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('review-images', 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        Review::create([
            'user_id'     => $userId,
            'product_id'  => $productId,
            'rating'      => $data['rating'],
            'title'       => $data['title'] ?? null,
            'comment'     => $data['comment'],
            'images'      => !empty($imagePaths) ? $imagePaths : null,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi, admin sẽ xem xét và duyệt.');
    }
}

