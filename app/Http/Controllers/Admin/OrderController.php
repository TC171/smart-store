<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->with(['user', 'payment'])
            ->withCount('items')
            ->latest('created_at');

        // Lọc theo trạng thái đơn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo trạng thái thanh toán
        // Theo DB bạn gửi thì payment_status nằm ở bảng orders
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Tìm kiếm theo mã đơn / tên người nhận / số điện thoại
        if ($request->filled('q')) {
            $keyword = trim($request->q);

            $query->where(function ($sub) use ($keyword) {
                $sub->where('order_number', 'like', "%{$keyword}%")
                    ->orWhere('shipping_name', 'like', "%{$keyword}%")
                    ->orWhere('shipping_phone', 'like', "%{$keyword}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        // AJAX
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.orders.partials.table', compact('orders'))->render(),
                'pagination' => view('admin.orders.partials.pagination', compact('orders'))->render(),
            ]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in([
                'pending',
                'confirmed',
                'shipping',
                'completed',
                'cancelled',
                'refunded',
            ])],
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}