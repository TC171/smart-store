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

                    if ($image) {
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
        $aiFilters = [
            'pin_min' => null,
            'ram_min' => null,
            'screen_min' => null,
            'storage_min' => null
        ];

        if (!$isOrderQuestion) {
            try {

                $intentPrompt = "
Trích xuất yêu cầu sản phẩm từ câu sau:

\"$userMessage\"

Quy đổi về số:
- pin trâu = >= 4000mAh
- RAM cao = >= 8GB
- màn hình lớn = >= 6.5 inch
- dung lượng cao = >= 128GB

Trả về JSON với cấu trúc như sau:
{
  pin_min: number|null,         // Yêu cầu pin tối thiểu (mAh)
  ram_min: number|null,         // Yêu cầu RAM tối thiểu (GB)
  screen_min: number|null,      // Yêu cầu màn hình tối thiểu (inch)
  storage_min: number|null      // Yêu cầu bộ nhớ tối thiểu (GB)
}
";

                $intentRes = Http::timeout(10)->withHeaders([
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json'
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                    "model" => "llama-3.1-8b-instant",
                    "messages" => [
                        ["role" => "user", "content" => $intentPrompt]
                    ]
                ]);

                $intentData = $intentRes->json();
                $intentText = $intentData['choices'][0]['message']['content'] ?? '{}';

                $intentJson = json_decode($intentText, true);

                if (is_array($intentJson)) {
                    $aiFilters = array_merge($aiFilters, $intentJson);
                }

            } catch (\Exception $e) {
                // fail silently
            }
        }

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
   if ($aiFilters['pin_min']) {
    $query->whereExists(function ($q) use ($aiFilters) {
        $q->select(DB::raw(1))
          ->from('product_specs')
          ->whereColumn('product_specs.product_id', 'products.id')
          ->where('name', 'Pin')
          ->whereRaw('CAST(value AS UNSIGNED) >= ?', [$aiFilters['pin_min']]);
    });
}

if ($aiFilters['ram_min']) {
    $query->whereExists(function ($q) use ($aiFilters) {
        $q->select(DB::raw(1))
          ->from('product_specs')
          ->whereColumn('product_specs.product_id', 'products.id')
          ->where('name', 'RAM')
          ->whereRaw('CAST(value AS UNSIGNED) >= ?', [$aiFilters['ram_min']]);
    });
}

if ($aiFilters['screen_min']) {
    $query->whereExists(function ($q) use ($aiFilters) {
        $q->select(DB::raw(1))
          ->from('product_specs')
          ->whereColumn('product_specs.product_id', 'products.id')
          ->where('name', 'Màn hình')
          ->whereRaw('CAST(value AS DECIMAL(5,2)) >= ?', [$aiFilters['screen_min']]);
    });
}

if ($aiFilters['storage_min']) {
    $query->whereExists(function ($q) use ($aiFilters) {
        $q->select(DB::raw(1))
          ->from('product_specs')
          ->whereColumn('product_specs.product_id', 'products.id')
          ->where('name', 'Bộ nhớ')
          ->whereRaw('CAST(value AS UNSIGNED) >= ?', [$aiFilters['storage_min']]);
    });
}

    // Get filtered products
    $products = $query
        ->with('variants')
        ->select('id','name','slug','category_id')
        ->orderBy('id', 'desc')
        ->limit(100)
        ->get();

    foreach ($products as $p) {
        // ✅ LẤY THÔNG SỐ THẬT
$specs = DB::table('product_specs')
    ->where('product_id', $p->id)
    ->pluck('value', 'name');

// ✅ BUILD CHUỖI THÔNG SỐ
$realSpecs = "";

foreach ($specs as $name => $value) {
    $realSpecs .= "$name: $value | ";
}

$realSpecs = rtrim($realSpecs, " | ");

        $image = DB::table('product_images')
            ->where('product_id', $p->id)
            ->orderBy('is_main', 'desc')
            ->orderBy('id', 'asc')
            ->value('image');

        $imageUrl = $image
            ? asset('storage/' . $image)
            : asset('images/no-image.png');

        $categorySlug = DB::table('categories')
            ->where('id', $p->category_id)
            ->value('slug') ?? 'san-pham';

        $link = url("/{$categorySlug}/{$p->slug}");

        $minPrice = $p->variants->min('price');
        $maxPriceVar = $p->variants->max('price');

        if ($minPrice && $maxPriceVar && $minPrice != $maxPriceVar) {
            $priceText = number_format($minPrice) . "đ - " . number_format($maxPriceVar) . "đ";
        } else {
            $priceText = number_format($minPrice ?? 0) . "đ";
        }

        // Thêm phần nhắc lại thông số yêu cầu
        $specText = "";
        if ($aiFilters['pin_min']) {
            $specText .= "Pin >= {$aiFilters['pin_min']}mAh | ";
        }
        if ($aiFilters['ram_min']) {
            $specText .= "RAM >= {$aiFilters['ram_min']}GB | ";
        }
        if ($aiFilters['screen_min']) {
            $specText .= "Màn hình >= {$aiFilters['screen_min']} inch | ";
        }
        if ($aiFilters['storage_min']) {
            $specText .= "Bộ nhớ >= {$aiFilters['storage_min']}GB | ";
        }

        // Loại bỏ dấu | dư thừa
        $specText = rtrim($specText, " | ");

        $productList .= "{$p->name} | "
            . $priceText . " | "
            . $link . " | "
            . $imageUrl . "\n"
              . "Thông số: {$realSpecs}\n"
    . "Yêu cầu: {$specText}\n";
            
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
- Nếu khách hỏi thông số:
  + BẮT BUỘC liệt kê thông số từ dữ liệu 'Thông số:'
  + Không được trả lời chung chung
- Nếu là sản phẩm → format:
Tên | Giá | Link | Ảnh
+ Thông số: ...

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