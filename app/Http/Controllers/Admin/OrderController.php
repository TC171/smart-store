<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])
            ->withCount(['items'])
            ->latest('created_at');

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTER PAYMENT STATUS
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // SEARCH
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('shipping_name', 'like', "%{$q}%")
                    ->orWhere('shipping_phone', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        // AJAX => trả JSON gồm table + pagination
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
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled,refunded',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}