<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Coupon; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm tạm hết hàng'], 400);
            }
            return back()->with('error', 'Sản phẩm tạm hết hàng');
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
                'image' => $variant->image 
                    ? asset('storage/'.$variant->image) 
                    : ($variant->product->thumbnail 
                        ? asset('storage/'.$variant->product->thumbnail) 
                        : asset('images/no-image.jpg')),
            ];
        }

        $cart[$key]['quantity'] += $quantity;
        session(['cart' => $cart]);

        if ($request->has('buy_now')) {
            session(['checkout_items' => [$key => $cart[$key]]]);
            return redirect()->route('checkout.index')->with('success', 'Chuyển đến thanh toán');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => count($cart),
                'cart' => $cart
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = session('cart', []);
        
        if (! isset($cart[$id])) return response()->json(['success' => false], 404);

        $cart[$id]['quantity'] = (int) $request->quantity;
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'subtotal' => $cart[$id]['price'] * $cart[$id]['quantity']
        ]);
    }

    public function remove(string $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Đã xóa sản phẩm');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);

        if ($request->has('selected_items')) {
            $selectedIds = $request->input('selected_items', []);
            if (empty($selectedIds)) {
                return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất 1 sản phẩm');
            }
            $checkoutItems = array_intersect_key($cart, array_flip($selectedIds));
            session(['checkout_items' => $checkoutItems]);
        }

        if (empty(session('checkout_items', []))) {
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm để thanh toán');
        }

        session()->forget('coupon');

        return view('frontend.checkout');
    }

    public function getAvailableCoupons(Request $request)
    {
        try {
            $now = now();
            $coupons = Coupon::where('status', 1)
                ->where(function($query) use ($now) {
                    $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                })
                ->get();

            if (!$request->wantsJson() && !$request->ajax()) {
                return view('frontend.api_docs.coupons', compact('coupons'));
            }

            return response()->json($coupons->values());
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->where('status', 1)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại']);
        }

        if ($coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã đã hết lượt sử dụng']);
        }

        if ($coupon->expires_at && $coupon->expires_at < now()) {
             return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn']);
        }

        $checkoutItems = session('checkout_items', []);
        $total = collect($checkoutItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount, 0, ',', '.') . 'đ'
            ]);
        }

        $discount = ($coupon->type === 'fixed') 
                    ? (float)$coupon->value 
                    : ($total * (float)$coupon->value / 100);

        if ($coupon->max_discount && $coupon->max_discount > 0) {
            $discount = min($discount, (float)$coupon->max_discount);
        }

        $discount = min($discount, $total);

        session(['coupon' => [
            'id' => $coupon->id, // 🔥 Lưu ID thay vì Code để khớp Database
            'code' => $coupon->code,
            'discount' => $discount
        ]]);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'new_total' => $total - $discount,
            'code' => $coupon->code,
            'message' => 'Áp dụng mã thành công'
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) {
            return redirect()->route('cart.index')->with('error', 'Lỗi phiên thanh toán');
        }

        $couponSession = session('coupon');
        $discountAmount = $couponSession ? $couponSession['discount'] : 0;

        $createdOrder = null; 

        DB::transaction(function () use ($request, $checkoutItems, $discountAmount, $couponSession, &$createdOrder) {
            $totalAmount = collect($checkoutItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
            $grandTotal = $totalAmount - $discountAmount;

            $createdOrder = Order::create([
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.mt_rand(1000, 9999),
                'user_id' => auth('web')->id(),
                'coupon_id' => $couponSession['id'] ?? null, // 🔥 Dùng coupon_id (Đã có trong Model của bạn)
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'grand_total' => max(0, $grandTotal),
                'shipping_fee' => 0,
                'tax_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $request->name,
                'shipping_phone' => $request->phone,
                'shipping_address' => $request->address,
                'ordered_at' => now(),
            ]);

            foreach ($checkoutItems as $item) {
                OrderItem::create([
                    'order_id' => $createdOrder->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            if ($request->payment_method === 'cod' && $couponSession) {
                Coupon::where('id', $couponSession['id'])->increment('used_count');
            }
        });

        $cart = session('cart', []);
        foreach ($checkoutItems as $id => $item) {
            unset($cart[$id]);
        }
        session(['cart' => $cart]);
        session()->forget(['checkout_items', 'coupon']);

        if ($request->payment_method === 'cod') {
            try {
                Mail::to($request->email)->send(new OrderConfirmed($createdOrder));
            } catch (\Exception $e) {
                \Log::error("Mail Error: " . $e->getMessage());
            }
            return redirect()->route('customer.orders')->with('success', 'Đặt hàng thành công!');
        }

        if ($request->payment_method === 'vnpay') {
            $vnp_Amount = (int)round($createdOrder->grand_total * 100);

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => env('VNPAY_TMN_CODE'),
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $request->ip(),
                "vnp_Locale" => 'vn',
                "vnp_OrderInfo" => "Thanh toan don hang " . $createdOrder->order_number,
                "vnp_OrderType" => 'billpayment',
                "vnp_ReturnUrl" => env('VNPAY_RETURN_URL'),
                "vnp_TxnRef" => $createdOrder->order_number,
            );

            ksort($inputData);
            $query = ""; $i = 0; $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) { $hashdata .= '&' . urlencode($key) . "=" . urlencode($value); }
                else { $hashdata .= urlencode($key) . "=" . urlencode($value); $i = 1; }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = env('VNPAY_URL') . "?" . $query;
            if (env('VNPAY_HASH_SECRET')) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, env('VNPAY_HASH_SECRET'));
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return redirect()->away($vnp_Url);
        }

        return redirect()->route('customer.orders');
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") { $inputData[$key] = $value; }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0; $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) { $hashData .= '&' . urlencode($key) . "=" . urlencode($value); }
            else { $hashData .= urlencode($key) . "=" . urlencode($value); $i = 1; }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $order = Order::where('order_number', $request->vnp_TxnRef)->first();

        if ($secureHash == $request->vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                if ($order) {
                    $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
                    if ($order->coupon_id) {
                        Coupon::where('id', $order->coupon_id)->increment('used_count');
                    }
                }
                return view('frontend.checkout.success', compact('order', 'request'));
            }
            return redirect()->route('cart.index')->with('error', 'Giao dịch thất bại.');
        }
        return redirect()->route('cart.index')->with('error', 'Chữ ký không hợp lệ.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth('web')->id())->firstOrFail();

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Đơn hàng không thể hủy.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            foreach ($order->items as $item) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                    DB::table('inventory_history')->insert([
                        'product_variant_id' => $variant->id,
                        'type' => 'return',
                        'quantity' => $item->quantity,
                        'previous_stock' => $variant->stock - $item->quantity,
                        'current_stock' => $variant->stock,
                        'reference_type' => 'order_cancel',
                        'reference_id' => $order->id,
                        'notes' => 'Hủy đơn #' . $order->order_number,
                        'created_at' => now(),
                    ]);
                }
            }
        });

        return back()->with('success', 'Đã hủy đơn hàng thành công.');
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', auth('web')->id())->orderBy('created_at', 'DESC')->paginate(10);
        return view('frontend.customer.orders', compact('orders'));
    }
    
}