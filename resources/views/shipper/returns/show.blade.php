@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('shipper.returns.index') }}" class="text-gray-400 hover:text-white transition">← Đơn hoàn hàng</a>
    <span class="text-gray-600">/</span>
    <h1 class="text-xl font-bold text-white">{{ $return->order->order_number ?? 'Chi tiết' }}</h1>
</div>

@if(session('success'))
<div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Thông tin hoàn hàng --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Trạng thái hiện tại --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">📊 Trạng thái hiện tại</h2>
            <div class="flex items-center gap-3 p-4 rounded-xl border {{ $return->status_color }}">
                <div class="text-3xl">{{ $return->status_icon }}</div>
                <div>
                    <div class="font-bold text-base">{{ $return->status_label }}</div>
                    @if($return->picked_up_at)
                    <div class="text-xs opacity-70 mt-0.5">Lấy hàng lúc: {{ $return->picked_up_at->format('H:i, d/m/Y') }}</div>
                    @endif
                    @if($return->returned_at)
                    <div class="text-xs opacity-70 mt-0.5">Về shop lúc: {{ $return->returned_at->format('H:i, d/m/Y') }}</div>
                    @endif
                </div>
            </div>

            @if($return->return_code)
            <div class="mt-4 p-3 bg-gray-800 rounded-lg">
                <div class="text-gray-400 text-xs mb-1">Mã hoàn hàng</div>
                <div class="font-mono text-cyan-400 font-bold text-lg tracking-wider">{{ $return->return_code }}</div>
            </div>
            @endif

            {{-- Timeline trạng thái --}}
            <div class="mt-5">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-3">Tiến trình</p>
                @php
                $steps = [
                    ['status' => 'approved_return',   'icon' => '📋', 'label' => 'Admin giao việc'],
                    ['status' => 'shipper_picking',   'icon' => '🚚', 'label' => 'Đã lấy hàng từ khách'],
                    ['status' => 'shipper_returning', 'icon' => '🔄', 'label' => 'Đang về shop'],
                    ['status' => 'goods_received',    'icon' => '📦', 'label' => 'Hàng đã về shop'],
                    ['status' => 'refunded',          'icon' => '✅', 'label' => 'Đã hoàn tiền'],
                ];
                $orderStatuses = array_column($steps, 'status');
                $currentIndex = array_search($return->status, $orderStatuses);
                @endphp
                <div class="space-y-2">
                    @foreach($steps as $i => $step)
                    @php
                    $done    = $currentIndex !== false && $i <= $currentIndex;
                    $current = $currentIndex !== false && $i === $currentIndex;
                    @endphp
                    <div class="flex items-center gap-3 {{ $done ? '' : 'opacity-40' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm
                            {{ $current ? 'bg-blue-600 ring-2 ring-blue-400' : ($done ? 'bg-green-600' : 'bg-gray-700') }}">
                            {{ $step['icon'] }}
                        </div>
                        <span class="text-sm {{ $current ? 'text-white font-bold' : ($done ? 'text-gray-300' : 'text-gray-600') }}">
                            {{ $step['label'] }}
                        </span>
                        @if($current)
                        <span class="text-blue-400 text-xs font-bold">← Hiện tại</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        @if(in_array($return->status, ['approved_return','shipper_picking','shipper_returning']))
        <div class="bg-gray-900 border border-blue-500/30 rounded-xl p-5">
            <h2 class="font-bold text-white mb-4">⚡ Cập nhật trạng thái</h2>

            @if($return->status === 'approved_return')
            <div class="bg-cyan-500/10 border border-cyan-500/30 rounded-lg p-4 mb-4">
                <p class="text-cyan-300 text-sm">📍 Đến địa chỉ khách hàng để lấy hàng, sau đó bấm xác nhận bên dưới.</p>
                <p class="text-gray-400 text-xs mt-1">
                    Địa chỉ: {{ collect([$return->order->shipping_address, $return->order->shipping_district, $return->order->shipping_city])->filter()->implode(', ') }}
                </p>
            </div>
            <form action="{{ route('shipper.returns.pickup', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đã lấy hàng từ khách tại địa chỉ này?')">
                @csrf
                <button type="submit"
                    class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 rounded-xl text-sm transition">
                    🚚 Xác nhận đã lấy hàng từ khách
                </button>
            </form>

            @elseif($return->status === 'shipper_picking')
            <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-lg p-4 mb-4">
                <p class="text-indigo-300 text-sm">🔄 Đã có hàng, bấm xác nhận để ghi nhận đang trên đường về shop.</p>
            </div>
            <form action="{{ route('shipper.returns.returning', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đang trên đường về shop?')">
                @csrf
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl text-sm transition">
                    🔄 Xác nhận đang chuyển về shop
                </button>
            </form>

            @elseif($return->status === 'shipper_returning')
            <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4 mb-4">
                <p class="text-orange-300 text-sm">📦 Khi đã về đến shop và giao hàng, bấm xác nhận để Admin kiểm tra và hoàn tiền.</p>
            </div>
            <form action="{{ route('shipper.returns.delivered', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đã giao hàng về shop? Thao tác này không thể hoàn tác.')">
                @csrf
                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl text-sm transition">
                    📦 Xác nhận đã giao hàng về shop
                </button>
            </form>
            @endif
        </div>
        @endif

        {{-- Lý do hoàn hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3">
                📝 Lý do hoàn hàng
                <span class="text-xs font-normal text-gray-400 ml-2">({{ $return->type_label }})</span>
            </h2>
            <p class="text-gray-300 bg-gray-800 rounded-lg p-4 text-sm leading-relaxed italic">{{ $return->reason }}</p>
            @if($return->admin_note)
            <div class="mt-3">
                <div class="text-gray-400 text-xs mb-1">Ghi chú từ Admin</div>
                <p class="text-gray-300 bg-blue-500/10 border border-blue-500/20 rounded-lg p-3 text-sm">{{ $return->admin_note }}</p>
            </div>
            @endif
        </div>

        {{-- Video bằng chứng --}}
        @if($return->video_path)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3">🎥 Video bằng chứng từ khách</h2>
            <video controls class="w-full rounded-lg max-h-72 bg-black">
                <source src="{{ asset('storage/' . $return->video_path) }}" type="video/mp4">
                Trình duyệt không hỗ trợ video.
            </video>
        </div>
        @endif

        {{-- Sản phẩm --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">🛍️ Sản phẩm cần hoàn ({{ $return->order->items->count() }} sản phẩm)</h2>
            <div class="space-y-3">
                @foreach($return->order->items as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                    @if($item->variant && $item->variant->image)
                    <img src="{{ asset('storage/' . $item->variant->image) }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                    @else
                    <div class="w-12 h-12 rounded-lg bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-500 text-xs">🖼️</span>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ $item->product_name }}</div>
                        @if($item->variant)
                        <div class="text-gray-400 text-xs">{{ collect([$item->variant->color, $item->variant->storage, $item->variant->ram])->filter()->implode(' / ') }}</div>
                        @endif
                    </div>
                    <div class="text-right text-sm shrink-0">
                        <div class="text-gray-300">x{{ $item->quantity }}</div>
                        <div class="text-cyan-400 font-medium">{{ number_format($item->subtotal ?? $item->price * $item->quantity, 0, ',', '.') }}đ</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: Thông tin khách & đơn hàng --}}
    <div class="space-y-5">

        {{-- Khách hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">👤 Khách hàng</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="text-gray-400 text-xs mb-0.5">Tên</div>
                    <div class="text-white font-medium">{{ $return->user->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-0.5">Điện thoại</div>
                    <div class="text-gray-200">{{ $return->order->shipping_phone ?? $return->user->phone ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-0.5">Email</div>
                    <div class="text-gray-300 text-xs truncate">{{ $return->user->email ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Địa chỉ lấy hàng --}}
        <div class="bg-gray-900 border border-blue-500/30 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3">📍 Địa chỉ lấy hàng</h2>
            <div class="text-sm space-y-1">
                <div class="text-white font-bold">{{ $return->order->shipping_name }}</div>
                <div class="text-blue-300 font-medium">📞 {{ $return->order->shipping_phone }}</div>
                <div class="text-gray-300 leading-relaxed">
                    {{ collect([$return->order->shipping_address, $return->order->shipping_district, $return->order->shipping_city])->filter()->implode(', ') }}
                </div>
            </div>
        </div>

        {{-- Thông tin đơn --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">📋 Thông tin đơn</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Mã đơn</span>
                    <span class="text-white font-mono font-bold">{{ $return->order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Tổng tiền</span>
                    <span class="text-cyan-400 font-bold">{{ number_format($return->order->grand_total ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Ngày yêu cầu</span>
                    <span class="text-gray-300">{{ $return->created_at->format('d/m/Y') }}</span>
                </div>
                @if($return->return_code)
                <div class="flex justify-between items-center pt-1 border-t border-gray-700">
                    <span class="text-gray-400">Mã hoàn</span>
                    <span class="text-cyan-400 font-mono font-bold text-xs">{{ $return->return_code }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Ghi chú --}}
        @if(in_array($return->status, ['goods_received', 'refunded']))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4">
            <p class="text-green-400 text-sm font-semibold">
                @if($return->status === 'goods_received') 📦 Hàng đã về shop, chờ Admin xác nhận hoàn tiền.
                @else ✅ Hoàn tất! Admin đã xác nhận hoàn tiền cho khách.
                @endif
            </p>
        </div>
        @endif

    </div>
</div>

@endsection