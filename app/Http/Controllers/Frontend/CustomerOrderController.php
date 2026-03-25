<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

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

        return view('customer.order-detail', compact('order'));
    }
}

