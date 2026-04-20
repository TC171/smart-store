<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Product; 
use App\Models\Coupon; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdated;
use Exception;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index() { return view('frontend.cart.index'); }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ], [
            'variant_id.required' => 'Vui lòng chọn một phiên bản (màu sắc/dung lượng) trước khi mua.',
            'variant_id.exists' => 'Phiên bản không tồn tại.',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $quantity = (int) ($request->quantity ?? 1);
        $stock = (int) $variant->stock;

        if (! $variant->status) {
            if ($request->ajax() || $request->wantsJson()) return response()->json(['success' => false, 'message' => 'Phiên bản này đã ngừng kinh doanh.'], 400);
            return back()->with('error', 'Phiên bản này đã ngừng kinh doanh.');
        }

        if ($stock < $quantity) {
            if ($request->ajax() || $request->wantsJson()) return response()->json(['success' => false, 'message' => 'Sản phẩm không đủ số lượng (Kho chỉ còn ' . $stock . ').'], 400);
            return back()->with('error', 'Sản phẩm không đủ số lượng (Kho chỉ còn ' . $stock . ').');
        }

        $cart = session('cart', []);
        $key = (string) $variant->id;

        if (! isset($cart[$key])) {
            $cart[$key] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'name' => $variant->product->name,
                'variant' => trim(($variant->color ?? '').' '.($variant->storage ?? '').' '.($variant->ram ?? '')),
                'color' => $variant->color,
                'storage' => $variant->storage,
                'ram' => $variant->ram,
                'price' => (float) ($variant->sale_price ?: $variant->price),
                'quantity' => 0,
                'image' => $this->resolveVariantImageUrl($variant),
            ];
        }

        $newQuantity = $cart[$key]['quantity'] + $quantity;

                if ($stock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm đã hết hàng!'
                    ], 400);
                }

                if ($newQuantity > $stock) {
                    $remain = $stock - $cart[$key]['quantity'];

                    return response()->json([
                        'success' => false,
                        'message' => $remain > 0
                            ? "Bạn chỉ có thể thêm tối đa $remain sản phẩm nữa."
                            : "Bạn đã thêm tối đa số lượng tồn kho vào giỏ hàng."
                    ], 400);
                }

        $cart[$key]['quantity'] = $newQuantity;
        session(['cart' => $cart]);

        if ($request->has('buy_now') || $request->buy_now == 1 || $request->buy_now == 'true') {
            session(['checkout_items' => [$key => $cart[$key]]]);
            if ($request->ajax() || $request->wantsJson()) return response()->json(['success' => true, 'redirect' => route('checkout.index')]);
            return redirect()->route('checkout.index');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng', 'cart_count' => count($cart), 'cart' => $cart]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function resolveVariantImageUrl(ProductVariant $variant)
    {
        if ($variant->image) {
            if (Str::startsWith($variant->image, ['http://', 'https://'])) {
                return $variant->image;
            }

            if (Str::startsWith($variant->image, 'storage/')) {
                return asset($variant->image);
            }

            return asset('storage/' . ltrim($variant->image, '/'));
        }

        if ($variant->product && $variant->product->thumbnail) {
            $thumbnail = $variant->product->thumbnail;
            if (Str::startsWith($thumbnail, ['http://', 'https://'])) {
                return $thumbnail;
            }
            if (Str::startsWith($thumbnail, 'storage/')) {
                return asset($thumbnail);
            }
            return asset('storage/' . ltrim($thumbnail, '/'));
        }

        return asset('images/no-image.jpg');
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = session('cart', []);
        
        if (! isset($cart[$id])) return response()->json(['success' => false], 404);

        $variant = ProductVariant::find($cart[$id]['variant_id']);
        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Phiên bản sản phẩm không tồn tại.'], 404);
        }

        $quantity = (int) $request->quantity;
        $availableStock = (int) $variant->stock;

        if ($availableStock < $quantity) {
            $cart[$id]['quantity'] = $availableStock;
            session(['cart' => $cart]);

            return response()->json([
                'success' => false,
                'message' => 'Kho hàng hiện không đủ sản phẩm. Số lượng đã được điều chỉnh về mức tồn kho hiện có.',
                'quantity' => $availableStock,
                'subtotal' => $cart[$id]['price'] * $availableStock,
                'available_stock' => $availableStock,
            ]);
        }

        $cart[$id]['quantity'] = $quantity;
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'quantity' => $cart[$id]['quantity'],
            'subtotal' => $cart[$id]['price'] * $cart[$id]['quantity'],
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
            if (empty($selectedIds)) return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất 1 sản phẩm');
            $checkoutItems = array_intersect_key($cart, array_flip($selectedIds));
            session(['checkout_items' => $checkoutItems]);
        }

        if (empty(session('checkout_items', []))) return redirect()->route('cart.index')->with('error', 'Không có sản phẩm để thanh toán');
        session()->forget('coupon');
        return view('frontend.checkout');
    }

    public function getAvailableCoupons(Request $request)
    {
        try {
            $coupons = Coupon::where('status', 1)
                ->whereRaw('(starts_at IS NULL OR starts_at <= NOW())')
                ->whereRaw('(expires_at IS NULL OR expires_at >= NOW())')
                ->whereRaw('(usage_limit IS NULL OR used_count < usage_limit)')
                ->get();

            $coupons = $coupons->map(function ($coupon) {
                return array_merge($coupon->toArray(), [
                    'start_date' => $coupon->starts_at ? $coupon->starts_at->toDateTimeString() : null,
                    'end_date' => $coupon->expires_at ? $coupon->expires_at->toDateTimeString() : null,
                ]);
            });

            return response()->json($coupons->values());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = trim($request->input('code'));
        if ($code === '') {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
        }

        $coupon = Coupon::where('status', 1)
            ->whereRaw('LOWER(code) = ?', [Str::lower($code)])
            ->first();

        if (!$coupon) return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại']);
        
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã đã hết lượt sử dụng']);
        }
        if ($coupon->starts_at && Carbon::parse($coupon->starts_at)->isFuture()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa tới thời gian áp dụng']);
        }
        if ($coupon->expires_at && Carbon::parse($coupon->expires_at)->isPast()) {
             return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn']);
        }

        $checkoutItems = session('checkout_items', []);
        $total = collect($checkoutItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount, 0, ',', '.') . 'đ']);
        }

        $discount = ($coupon->type === 'fixed') ? (float)$coupon->value : ($total * (float)$coupon->value / 100);
        if ($coupon->max_discount && $coupon->max_discount > 0) $discount = min($discount, (float)$coupon->max_discount);
        $discount = min($discount, $total);

        session(['coupon' => ['id' => $coupon->id, 'code' => $coupon->code, 'discount' => $discount]]);

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
            'phone' => ['required', 'regex:/^[0-9]{10,11}$/'],
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,vnpay',
        ], [
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng chỉ nhập số (từ 10 đến 11 số).'
        ]);

        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) return redirect()->route('cart.index')->with('error', 'Lỗi phiên thanh toán');

        $couponSession = session('coupon');
        $discountAmount = $couponSession ? $couponSession['discount'] : 0;
        $createdOrder = null; 

        try {
            DB::transaction(function () use ($request, $checkoutItems, $discountAmount, $couponSession, &$createdOrder) {
                $totalAmount = collect($checkoutItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
                $grandTotal = $totalAmount - $discountAmount;

                // Kiểm tra giới hạn COD 10 triệu
                if ($request->payment_method === 'cod' && $grandTotal > 10000000) {
                    throw new Exception('Đơn hàng trên 10.000.000đ không hỗ trợ thanh toán COD. Vui lòng chọn phương thức thanh toán điện tử.');
                }

                $createdOrder = Order::create([
                    'order_number' => 'ORD-'.now()->format('YmdHis').'-'.mt_rand(1000, 9999),
                    'user_id' => auth('web')->id(),
                    'email' => $request->email,
                    'coupon_id' => $couponSession['id'] ?? null,
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
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                    if (!$variant || $variant->stock < $item['quantity']) {
                        throw new Exception('Sản phẩm ' . $item['name'] . ' hiện không đủ số lượng trong kho.');
                    }

                    OrderItem::create([
                        'order_id' => $createdOrder->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['variant_id'],
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    $variant->decrement('stock', $item['quantity']);
                    Product::where('id', $item['product_id'])->increment('sold_count', $item['quantity']);

                    DB::table('inventory_history')->insert([
                        'product_variant_id' => $variant->id,
                        'type' => 'sale',
                        'quantity' => $item['quantity'],
                        'previous_stock' => $variant->stock + $item['quantity'],
                        'current_stock' => $variant->stock,
                        'reference_type' => 'order',
                        'reference_id' => $createdOrder->id,
                        'notes' => 'Bán hàng đơn #' . $createdOrder->order_number,
                        'created_at' => now(),
                    ]);
                }

                if ($request->payment_method === 'cod' && $couponSession) {
                    Coupon::where('id', $couponSession['id'])->increment('used_count');
                }
            });
        } catch (Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $cart = session('cart', []);
        foreach ($checkoutItems as $id => $item) unset($cart[$id]);
        session(['cart' => $cart]);
        session()->forget(['checkout_items', 'coupon']);

        try {
            Mail::to($request->email)->send(new OrderConfirmed($createdOrder));
        } catch (\Exception $e) {
            \Log::error('Mail Error: ' . $e->getMessage());
        }

        if ($request->payment_method === 'cod') {
            return redirect()->route('customer.orders')->with([
                'success' => 'Đặt hàng thành công! Cảm ơn bạn đã tin tưởng Smart Store.',
            ]);
        }

        if ($request->payment_method === 'vnpay') {
            $vnp_Amount = (int)round($createdOrder->grand_total * 100);
            $inputData = [
                "vnp_Version" => "2.1.0", "vnp_TmnCode" => env('VNPAY_TMN_CODE'), "vnp_Amount" => $vnp_Amount, "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'), "vnp_CurrCode" => "VND", "vnp_IpAddr" => $request->ip(), "vnp_Locale" => 'vn',
                "vnp_OrderInfo" => "Thanh toan don hang " . $createdOrder->order_number, "vnp_OrderType" => 'billpayment',
                "vnp_ReturnUrl" => env('VNPAY_RETURN_URL'), "vnp_TxnRef" => $createdOrder->order_number,
            ];
            ksort($inputData);
            $query = ""; $i = 0; $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) { $hashdata .= '&' . urlencode($key) . "=" . urlencode($value); } else { $hashdata .= urlencode($key) . "=" . urlencode($value); $i = 1; }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
            $vnp_Url = env('VNPAY_URL') . "?" . $query;
            if (env('VNPAY_HASH_SECRET')) {
                $vnp_Url .= 'vnp_SecureHash=' . hash_hmac('sha512', $hashdata, env('VNPAY_HASH_SECRET'));
            }
            return redirect()->away($vnp_Url);
        }

        return redirect()->route('customer.orders');
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = [];
        foreach ($_GET as $key => $value) if (substr($key, 0, 4) == "vnp_") $inputData[$key] = $value;
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0; $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            else { $hashData .= urlencode($key) . "=" . urlencode($value); $i = 1; }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $order = Order::where('order_number', $request->vnp_TxnRef)->first();

        if ($secureHash == $request->vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                if ($order && $order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
                    if ($order->coupon_id) Coupon::where('id', $order->coupon_id)->increment('used_count');
                }
                return view('frontend.checkout.success', compact('order', 'request'));
            }

            // Xử lý khi thanh toán thất bại hoặc người dùng hủy
            if ($order && ($order->status === 'waiting_payment' || $order->status === 'pending')) {
                DB::transaction(function () use ($order) {
                    // 1. Khôi phục giỏ hàng từ đơn hàng bị lỗi
                    $cart = session('cart', []);
                    foreach ($order->items as $item) {
                        $cart[$item->product_variant_id] = [
                            'id' => $item->product_variant_id,
                            'product_id' => $item->product_id,
                            'variant_id' => $item->product_variant_id,
                            'name' => $item->product_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                        ];
                    }
                    session(['cart' => $cart]);

                    // 2. Hủy đơn hàng và khôi phục tồn kho
                    $order->update([
                        'status' => 'cancelled',
                        'note' => ($order->note ? $order->note . "\n" : "") . "[Hệ thống: Thanh toán VNPay thất bại/hủy - Đã khôi phục giỏ hàng]"
                    ]);

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
                                'notes' => 'Hủy tự động do thanh toán VNPay không thành công',
                                'created_at' => now(),
                            ]);
                        }
                    }
                });
            }

            return redirect()->route('cart.index')->with('error', 'Thanh toán không thành công. Giỏ hàng của bạn đã được khôi phục.');
        }
        return redirect()->route('cart.index')->with('error', 'Lỗi xác thực chữ ký VNPAY.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth('web')->id())->firstOrFail();

        if (in_array($order->status, ['completed', 'cancelled', 'refunded'])) {
            return back()->with('error', 'Đơn hàng này đã hoàn thành hoặc đã được xử lý nên không thể hủy nữa.');
        }

        if ($order->status === 'pending') {
            $request->validate(['cancel_reason' => 'nullable|string|max:500']);
        } else {
            $request->validate(['cancel_reason' => 'required|string|max:500'], ['cancel_reason.required' => 'Vui lòng chọn lý do hủy đơn.']);
        }

        $cancelReason = trim($request->cancel_reason ?: 'Hủy đơn trực tiếp từ khách hàng.');

        if ($order->status === 'pending') {
            DB::transaction(function () use ($order, $request, $cancelReason) {
                $paymentStatus = $order->payment_status;
                if ($paymentStatus === 'paid') $paymentStatus = 'refunded'; 

                $order->update(['status' => 'cancelled', 'payment_status' => $paymentStatus]);

                foreach ($order->items as $item) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                                DB::table('inventory_history')->insert([
                            'product_variant_id' => $variant->id, 'type' => 'return', 'quantity' => $item->quantity,
                            'previous_stock' => $variant->stock - $item->quantity, 'current_stock' => $variant->stock,
                            'reference_type' => 'order_cancel', 'reference_id' => $order->id,
                            'notes' => 'Hủy đơn #' . $order->order_number . ' - Lý do: ' . $cancelReason, 'created_at' => now(),
                        ]);
                    }
                    $product = Product::find($item->product_id);
                    if ($product && $product->sold_count >= $item->quantity) $product->decrement('sold_count', $item->quantity);
                    elseif ($product) $product->update(['sold_count' => 0]); 
                }
            });

            try {
                $recipient = $order->email ?: auth('web')->user()->email;
                Mail::to($recipient)->send(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                \Log::error('Mail Error: ' . $e->getMessage());
            }

            return back()->with('success', 'Đã hủy đơn hàng thành công.');
        }

        $order->note = trim(($order->note ? $order->note . "\n" : '') . 'Yêu cầu hủy đơn hàng: ' . $cancelReason);
        $order->save();

        try {
            $recipient = $order->email ?: auth('web')->user()->email;
            Mail::to($recipient)->send(new OrderStatusUpdated(
                $order,
                'Chúng tôi đã nhận được yêu cầu hủy đơn của bạn. Admin sẽ kiểm tra và phản hồi sớm nhất.',
                'Yêu cầu hủy đơn #' . $order->order_number . ' đã được gửi'
            ));
        } catch (\Exception $e) {
            \Log::error('Mail Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Yêu cầu hủy đơn hàng đã được gửi. Admin sẽ kiểm tra và phê duyệt.');
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', auth('web')->id())->orderBy('created_at', 'DESC')->paginate(10);
        return view('frontend.customer.orders', compact('orders'));
    }
}