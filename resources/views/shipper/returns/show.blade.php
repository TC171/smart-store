@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('shipper.returns.index') }}" class="text-gray-400 hover:text-white transition">← Đơn hoàn hàng</a>
    <span class="text-gray-600">/</span>
    <h1 class="text-xl font-bold text-white">{{ $return->order->order_number ?? 'Chi tiết' }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Thông tin chính --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Nút hành động nổi bật --}}
        @if($return->status === 'approved_return')
            <div class="bg-cyan-600 rounded-xl p-5 shadow-lg shadow-cyan-900/20">
                <h2 class="text-white font-bold mb-1 flex items-center gap-2">🚚 Bước 1: Lấy hàng từ khách</h2>
                <p class="text-cyan-100 text-xs mb-4">Bạn cần đến địa chỉ khách hàng, kiểm tra hàng và xác nhận đã cầm hàng.</p>
                <form action="{{ route('shipper.returns.pickup', $return) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white text-cyan-700 font-extrabold py-3 rounded-xl transition active:scale-95">
                        XÁC NHẬN ĐÃ LẤY HÀNG
                    </button>
                </form>
            </div>
        @elseif($return->status === 'shipper_returning')
            <div class="bg-orange-600 rounded-xl p-5 shadow-lg shadow-orange-900/20">
                <h2 class="text-white font-bold mb-1 flex items-center gap-2">📦 Bước 2: Bàn giao về Shop</h2>
                <p class="text-orange-100 text-xs mb-4">Bấm nút này khi bạn đã thực tế giao túi hàng hoàn cho Admin tại kho.</p>
                <form action="{{ route('shipper.returns.delivered', $return) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white text-orange-700 font-extrabold py-3 rounded-xl transition active:scale-95">
                        XÁC NHẬN ĐÃ VỀ KHO
                    </button>
                </form>
            </div>
        @endif

        {{-- Lý do và Bằng chứng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3 flex items-center gap-2">
                <span class="text-red-400">📝</span> Lý do khách yêu cầu hoàn
            </h2>
            <div class="bg-gray-800 border-l-4 border-red-500 p-4 text-sm text-gray-300 italic mb-4">
                "{{ $return->reason }}"
            </div>
            @if($return->video_path)
                <div class="rounded-lg overflow-hidden border border-gray-700">
                    <video controls class="w-full bg-black max-h-80">
                        <source src="{{ asset('storage/' . $return->video_path) }}">
                    </video>
                </div>
            @endif
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4 flex items-center gap-2">🛍️ Sản phẩm hoàn</h2>
            <div class="space-y-3">
                @foreach($return->order->items as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium">{{ $item->product_name }}</div>
                        <div class="text-gray-500 text-xs">SKU: {{ $item->sku }}</div>
                    </div>
                    <div class="text-right text-sm">
                        <div class="text-gray-300">x{{ $item->quantity }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: Thông tin liên hệ --}}
    <div class="space-y-5">
        {{-- Khách hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">👤 Khách hàng</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold">
                        {{ substr($return->user->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <div class="text-white font-bold text-sm">{{ $return->user->name ?? 'N/A' }}</div>
                        <a href="tel:{{ $return->order->shipping_phone }}" class="text-cyan-400 text-xs font-bold hover:underline">
                            📞 {{ $return->order->shipping_phone }}
                        </a>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-800">
                    <div class="text-gray-500 text-xs mb-1">Địa chỉ lấy hàng:</div>
                    <div class="text-gray-200 text-sm leading-relaxed">{{ $return->order->shipping_address }}</div>
                </div>
            </div>
        </div>

        {{-- Trạng thái timeline --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="text-white font-semibold mb-4 text-sm">🕰️ Lịch sử đơn hoàn</h2>
            <div class="space-y-4">
                @if($return->picked_up_at)
                    <div class="flex gap-3 text-xs">
                        <div class="text-green-500">✔</div>
                        <div class="text-gray-400">Đã lấy hàng từ khách: <span class="text-gray-200">{{ $return->picked_up_at->format('H:i, d/m') }}</span></div>
                    </div>
                @endif
                @if($return->returned_at)
                    <div class="flex gap-3 text-xs">
                        <div class="text-green-500">✔</div>
                        <div class="text-gray-400">Đã bàn giao về Shop: <span class="text-gray-200">{{ $return->returned_at->format('H:i, d/m') }}</span></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection