<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'reply' => 'Vui lòng đăng nhập để sử dụng tính năng này'
            ], 401);
        }
        // 🔴 CHECK AI ENABLED (ADMIN CONTROL)
$aiEnabled = DB::table('system_settings')
    ->where('setting_key', 'ai_enabled')
    ->value('setting_value');

if ($aiEnabled != '1') {
    return response()->json([
        'reply' => 'AI hiện đang tạm tắt'
    ]);
}

        $userMessage = $request->message;
// ✅ LƯU TIN NHẮN USER
DB::table('chats')->insert([
    'user_id' => auth()->id(),
    'message' => $userMessage,
    'sender' => 'user',
    'created_at' => now()
]);

        // =========================
        // 🔥 NHẬN DIỆN CÂU HỎI ĐƠN HÀNG
        // =========================
        $isOrderQuestion = preg_match('/đơn|order|giao|ship|bao lâu|khi nào|mới nhất|gần nhất/i', $userMessage);

        // =========================
        // 🔥 TÌM MÃ ĐƠN
        // =========================
        preg_match('/ORD-\d+-\d+/', $userMessage, $match);
        $orderCode = isset($match[0]) ? trim($match[0]) : null;

        $wantChooseAgain = preg_match('/(chọn|xem lại|đổi|khác|danh sách|tất cả)/i', $userMessage);
        // ✅ FIX: nếu không có mã → dùng lại đơn trước đó
        if (!$orderCode && !$wantChooseAgain && session()->has('last_order_code')) {
            $orderCode = session('last_order_code');
        }

        // =========================
        // 🔥 QUERY ĐƠN HÀNG
        // =========================
        if (!$orderCode && preg_match('/mới nhất|gần nhất/i', $userMessage)) {

            $orders = Order::where('user_id', auth()->id())
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();

        } elseif ($orderCode) {

            $orders = Order::where('order_number', 'LIKE', "%$orderCode%")
                ->where('user_id', auth()->id())
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'reply' => "❌ Không tìm thấy đơn hàng $orderCode (có thể không thuộc tài khoản của bạn)"
                ]);
            }

            // ✅ FIX: lưu lại đơn vừa hỏi
            session(['last_order_code' => $orders->first()->order_number]);

        } else {

            $orders = Order::where('user_id', auth()->id())
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        }

        // =========================
        // 🔥 XỬ LÝ HỎI MƠ HỒ
        // =========================
        if ($isOrderQuestion && !$orderCode && isset($orders) && $orders->count() > 1) {

            $buttons = [];

            foreach ($orders as $o) {

                $item = DB::table('order_items')
                    ->where('order_id', $o->id)
                    ->first();

                $productName = '';
                $imageUrl = asset('images/no-image.png');

                if ($item) {
                    $productName = $item->product_name;

                    $image = DB::table('product_images')
                        ->where('product_id', $item->product_id)
                        ->orderBy('is_main', 'desc')
                        ->orderBy('id', 'asc')
                        ->value('image');

                    if (!$image) {
                        $image = DB::table('products')
                            ->where('id', $item->product_id)
                            ->value('thumbnail');
                    }

                    if ($image) {

    $image = trim($image);
    $image = str_replace('\\', '/', $image);
    $image = preg_replace('#^/?storage/#', '', $image);
    $image = ltrim($image, '/');

    $imageUrl = asset('storage/' . $image);
}
                }

                $buttons[] = [
                    'order_number' => $o->order_number,
                    'label' => "Đơn {$o->order_number}",
                    'product_name' => $productName,
                    'image' => $imageUrl
                ];
            }

            return response()->json([
                'reply' => 'Bạn muốn xem đơn nào? Hãy chọn bên dưới 👇',
                'type' => 'choose_order',
                'orders' => $buttons
            ]);
        }

        // =========================
        // 🔥 BUILD ORDER LIST
        // =========================
        $orderList = "";

        foreach ($orders as $o) {

            $statusText = match ($o->status) {
                'pending'   => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'shipping'  => 'Đang giao',
                'complete'  => 'Đã giao',
                'cancelled' => 'Đã huỷ',
                default     => 'Không xác định'
            };

            $paymentText = match ($o->payment_status) {
                'pending', 'unpaid' => 'Chưa thanh toán',
                'paid', 'success'   => 'Đã thanh toán',
                'failed'            => 'Thanh toán thất bại',
                'cod'               => 'Thanh toán khi nhận hàng',
                null, ''            => 'Chưa thanh toán',
                default             => 'Không rõ'
            };

            $created = Carbon::parse($o->created_at);

            $etaDate = match ($o->status) {
                'pending'   => $created->copy()->addDays(5),
                'confirmed' => $created->copy()->addDays(4),
                'shipping'  => now()->addDays(2),
                'complete'  => null,
                default     => null
            };

            // ✅ FIX: sửa số ngày bị lẻ
            if ($o->status == 'completed') {
                $estimate = 'Đã giao';
            } elseif ($etaDate) {

                $daysLeft = max(0, ceil(now()->diffInDays($etaDate, false)));
                $daysText = $daysLeft == 0 ? 'hôm nay' : "còn {$daysLeft} ngày";

                $estimate = $etaDate->format('d/m') . " ({$daysText})";

            } else {
                $estimate = 'Chưa xác định';
            }

            $orderList .= "Mã: {$o->order_number} | "
                . "Trạng thái: {$statusText} | "
                . "Thanh toán: {$paymentText} | "
                . "Địa chỉ: {$o->shipping_address} | "
                . "Dự kiến: {$estimate}\n";
        }

        if ($orderList == "") {
            $orderList = "Không tìm thấy đơn hàng\n";
        }

        // =========================
        // 🔥 LỌC GIÁ
        // =========================
        $maxPrice = null;

        if (preg_match('/(\d+)\s*(triệu|tr)/i', $userMessage, $m)) {
            $maxPrice = $m[1] * 1000000;
        }

        // =========================
        // 🔥 AI NHẬN DIỆN YÊU CẦU (pin, RAM, màn hình)
        // =========================


  

        // =========================
        // 🔥 QUERY SẢN PHẨM DỰA TRÊN SPEC (AI filters)
        // =========================
        // =========================
// 🔥 QUERY SẢN PHẨM DỰA TRÊN SPEC (AI filters)
// =========================
$productList = "";

if (!$isOrderQuestion) {

    $query = Product::where('status', 1);

    if ($maxPrice) {
        $query->where('price', '<=', $maxPrice);
    }

    // Apply AI filters for specs
  

    // Get filtered products
    $products = $query
        ->with('variants')
        ->select('id','name','slug','category_id', 'thumbnail', 'price')
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();

    foreach ($products as $p) {


$imageUrl = asset('images/no-image.png');

if ($p->thumbnail) {
    $img = trim($p->thumbnail);
    $img = str_replace('\\', '/', $img);
    $img = preg_replace('#^/?storage/#', '', $img);
    $img = ltrim($img, '/');
    $fullPath = public_path('storage/' . $img);

    if (file_exists($fullPath)) {
        $imageUrl = asset('storage/' . $img);
    }
}

if ($imageUrl == asset('images/no-image.png')) {
    $images = DB::table('product_images')
        ->where('product_id', $p->id)
        ->orderBy('is_main', 'desc')
        ->orderBy('id', 'asc')
        ->pluck('image');

    foreach ($images as $img) {
        $img = trim($img);
        $img = str_replace('\\', '/', $img);
        $img = preg_replace('#^/?storage/#', '', $img);
        $img = ltrim($img, '/');

        $fullPath = public_path('storage/' . $img);

        // ✅ lấy ảnh đầu tiên hợp lệ
        if (file_exists($fullPath)) {
            $imageUrl = asset('storage/' . $img);
            break;
        }
    }
}

        $categorySlug = DB::table('categories')
            ->where('id', $p->category_id)
            ->value('slug') ?? 'san-pham';

        $link = url("/{$categorySlug}/{$p->slug}");

       $minPrice = $p->variants->min('price') ?? $p->price ?? 0;
$maxPriceVar = $p->variants->max('price') ?? $minPrice;
        $maxPriceVar = $p->variants->max('price');

        if ($minPrice && $maxPriceVar && $minPrice != $maxPriceVar) {
            $priceText = number_format($minPrice) . "đ - " . number_format($maxPriceVar) . "đ";
        } else {
            $priceText = number_format($minPrice ?? 0) . "đ";
        }

        // Thêm phần nhắc lại thông số yêu cầu
      

        // Loại bỏ dấu | dư thừa
   

        $productList .= "{$p->name}|{$priceText}|{$link}|{$imageUrl}\n";
            
    }
}

        // =========================
        // 🔥 PROMPT
        // =========================
$policyText = "
CHÍNH SÁCH ĐỔI TRẢ:

- Điều kiện:
+ Còn nguyên seal, chưa kích hoạt
+ Đầy đủ hộp, phụ kiện, hóa đơn
+ Không trầy xước, hư hỏng do người dùng

- Thời gian:
+ Lỗi NSX: 30 ngày (đổi mới / hoàn tiền)
+ Không ưng: 7 ngày (còn seal)
+ Giao sai: 3 ngày

- Không áp dụng:
+ Đã sử dụng, trầy xước
+ Đã kích hoạt bảo hành
+ Hàng giảm giá ghi rõ không đổi
+ Quá thời gian

- Hoàn tiền:
+ Chuyển khoản: 3–7 ngày
+ Tiền mặt tại cửa hàng
+ Lỗi NSX: shop chịu phí ship

----------------------

CHÍNH SÁCH BẢO HÀNH:

- Điều kiện:
+ Còn thời gian bảo hành
+ Tem, serial còn nguyên
+ Lỗi do nhà sản xuất
+ Có hóa đơn

- Thời gian:
+ Điện thoại: 12–24 tháng
+ Laptop: 12–36 tháng
+ Tablet: 12–24 tháng
+ Phụ kiện: 3–12 tháng

- Không bảo hành:
+ Rơi vỡ, vào nước
+ Sửa bên ngoài
+ Mất tem
+ Dùng sai cách
";
        
        $prompt = "
Bạn là chatbot bán hàng của Smart Store.

QUY TẮC:
- Nếu khách hỏi về đơn hàng → chỉ dùng dữ liệu đơn hàng bên dưới
- Nếu khách hỏi sản phẩm → chỉ dùng danh sách sản phẩm bên dưới
- Nếu hỏi chính sách → chỉ dùng CHÍNH SÁCH bên dưới
- Không được bịa dữ liệu
- Nếu không có → nói rõ không tìm thấy

📦 ĐƠN HÀNG:
$orderList

🛍 SẢN PHẨM:
$productList

📜 CHÍNH SÁCH:
$policyText

HƯỚNG DẪN:
- Trả lời tự nhiên như người thật
- Trả lời ngắn gọn, dễ hiểu
- Nếu hỏi đổi trả/bảo hành → trả lời đúng chính sách
- Nếu là đơn hàng:
  + Giải thích trạng thái bằng tiếng Việt
  + Báo thời gian dự kiến còn bao lâu
  + Nhắc lại địa chỉ giao hàng
  + Nếu 'Đang giao' → nói sắp nhận
  + Nếu 'Chờ xác nhận' → nói shop đang xử lý
  + Nếu 'Đã giao' → xác nhận đã hoàn tất
- Nếu là sản phẩm → format CHÍNH XÁC:
Tên|Giá|Link|Ảnh
- Không thêm gì khác
- Không xuống dòng giữa các field

Câu hỏi:
$userMessage
";
        try {
            $response = Http::timeout(20)->withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                "model" => "llama-3.1-8b-instant",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "Bạn là nhân viên CSKH chuyên nghiệp, trả lời ngắn gọn, đúng trọng tâm"
                    ],
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ]
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'reply' => 'LỖI API: ' . $response->body()
                ]);
            }

            $data = $response->json();

            $reply = $data['choices'][0]['message']['content']
                ?? 'AI không trả lời';
                // ✅ LƯU CHAT AI
DB::table('chats')->insert([
    'user_id' => auth()->id(),
    'message' => $reply,
    'sender' => 'ai',
    'created_at' => now()
]);

        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'LỖI SERVER: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'reply' => $reply
        ]);
    }
    public function history()
{
    return DB::table('chats')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'asc')
        ->get();
}
}