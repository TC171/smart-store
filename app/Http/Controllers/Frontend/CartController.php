<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.cart.index');
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $quantity = (int) ($request->quantity ?? 1);

        if (! $variant->status || $variant->stock < $quantity) {
            return back()->with('error', 'San pham tam het hang');
        }

        $cart = session('cart', []);
        $key = (string) $variant->id;

        if (! isset($cart[$key])) {
            $cart[$key] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'name' => $variant->product->name,
                'variant' => trim(($variant->color ?? '').' '.$variant->storage.' '.$variant->ram),
                'price' => (float) ($variant->sale_price ?: $variant->price),
                'quantity' => 0,
                'image' => $variant->image ? asset('storage/'.$variant->image) : ($variant->product->thumbnail ? asset('storage/'.$variant->product->thumbnail) : asset('images/no-image.jpg')),
            ];
        }

        $cart[$key]['quantity'] += $quantity;
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Da them vao gio hang');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        if (! isset($cart[$id])) {
            return back();
        }

        $cart[$id]['quantity'] = (int) $request->quantity;
        session(['cart' => $cart]);

        return back()->with('success', 'Da cap nhat gio hang');
    }

    public function remove(string $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Da xoa san pham');
    }

    public function checkout()
    {
        if (empty(session('cart', []))) {
            return redirect()->route('cart.index')->with('error', 'Gio hang dang trong');
        }

        return view('frontend.checkout');
    }

    public function placeOrder(Request $request)
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Gio hang dang trong');
        }

        DB::transaction(function () use ($request, $cart) {
            $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.mt_rand(1000, 9999),
                'user_id' => auth('web')->id(),
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'grand_total' => $subtotal,
                'shipping_fee' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $request->name,
                'shipping_phone' => $request->phone,
                'shipping_address' => $request->address,
                'ordered_at' => now(),
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'sku' => ProductVariant::find($item['variant_id'])?->sku,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }
        });

        session()->forget('cart');

        return redirect()->route('customer.orders')->with('success', 'Dat hang thanh cong');
    }
}
