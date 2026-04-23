@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">🔄 Đơn hoàn hàng của tôi</h1>
    <span class="text-gray-400 text-sm">{{ $returns->total() }} đơn</span>
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

{{-- Hướng dẫn luồng --}}
<div class="bg-blue-500/10 border border-blue-500/30 rounded-xl px-4 py-3 mb-6">
    <p class="text-blue-300 text-sm font-semibold mb-1">📋 Quy trình hoàn hàng:</p>
    <div class="flex flex-wrap gap-2 text-xs text-gray-400">
        <span class="bg-blue-500/20 text-blue-300 px-2 py-1 rounded">📋 Admin giao việc</span>
        <span class="text-gray-600">→</span>
        <span class="bg-cyan-500/20 text-cyan-300 px-2 py-1 rounded">🚚 Lấy hàng từ khách</span>
        <span class="text-gray-600">→</span>
        <span class="bg-indigo-500/20 text-indigo-300 px-2 py-1 rounded">🔄 Về shop</span>
        <span class="text-gray-600">→</span>
        <span class="bg-orange-500/20 text-orange-300 px-2 py-1 rounded">📦 Giao về shop</span>
        <span class="text-gray-600">→</span>
        <span class="bg-green-500/20 text-green-300 px-2 py-1 rounded">✅ Admin hoàn tiền</span>
    </div>
</div>

@if($returns->count() > 0)
<div class="space-y-4">
    @foreach($returns as $return)
    <div class="bg-gray-900 border {{ in_array($return->status, ['approved_return','shipper_picking','shipper_returning']) ? 'border-blue-500/40' : 'border-gray-800' }} rounded-xl p-5 transition">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-3 flex-wrap">
            <span class="text-white font-bold font-mono">{{ $return->order->order_number ?? 'N/A' }}</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $return->status_color }}">
                {{ $return->status_icon }} {{ $return->status_label }}
            </span>
            <span class="text-gray-500 text-xs">📅 {{ $return->created_at->format('d/m/Y H:i') }}</span>
        </div>

        {{-- Info --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm text-gray-400 mb-4">
            <div>👤 <span class="text-gray-200">{{ $return->user->name ?? 'N/A' }}</span></div>
            <div>📞 <span class="text-gray-200">{{ $return->user->phone ?? '—' }}</span></div>
            <div>📍 <span class="text-gray-300 text-xs truncate">{{ $return->order->shipping_address ?? '—' }}</span></div>
            @if($return->return_code)
            <div class="md:col-span-2">
                🏷️ Mã hoàn: <span class="text-cyan-400 font-mono font-bold">{{ $return->return_code }}</span>
            </div>
            @endif
            @if($return->picked_up_at)
            <div>🕐 Lấy hàng: <span class="text-gray-300">{{ $return->picked_up_at->format('H:i d/m') }}</span></div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex gap-2 flex-wrap items-center">
            <a href="{{ route('shipper.returns.show', $return) }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Xem chi tiết
            </a>

            @if($return->status === 'approved_return')
            <form action="{{ route('shipper.returns.pickup', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đã lấy hàng từ khách?')">
                @csrf
                <button type="submit"
                    class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    🚚 Đã lấy hàng từ khách
                </button>
            </form>

            @elseif($return->status === 'shipper_picking')
            <form action="{{ route('shipper.returns.returning', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đang trên đường về shop?')">
                @csrf
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    🔄 Đang chuyển về shop
                </button>
            </form>

            @elseif($return->status === 'shipper_returning')
            <form action="{{ route('shipper.returns.delivered', $return) }}" method="POST"
                  onsubmit="return confirm('Xác nhận đã giao hàng về shop?')">
                @csrf
                <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    📦 Đã giao về shop
                </button>
            </form>

            @elseif($return->status === 'goods_received')
            <span class="text-orange-400 text-sm font-medium">
                ⏳ Chờ Admin xác nhận hoàn tiền
            </span>

            @elseif($return->status === 'refunded')
            <span class="text-green-400 text-sm font-medium">
                ✅ Đã hoàn tiền cho khách
            </span>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $returns->links('pagination::tailwind') }}</div>
@else
<div class="text-center py-20 bg-gray-900 rounded-xl border border-gray-800">
    <div class="text-5xl mb-4">📭</div>
    <p class="text-gray-400">Chưa có đơn hoàn hàng nào được giao cho bạn.</p>
</div>
@endif

@endsection