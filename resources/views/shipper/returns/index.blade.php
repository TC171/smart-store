@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">🔄 Đơn hoàn hàng</h1>
    <span class="text-gray-400 text-sm">{{ $returns->total() }} đơn</span>
</div>

{{-- Hướng dẫn luồng rút gọn --}}
<div class="bg-indigo-500/10 border border-indigo-500/30 rounded-xl px-4 py-3 mb-6">
    <p class="text-indigo-300 text-xs font-semibold mb-2 uppercase tracking-wider">🚀 Quy trình 2 bước:</p>
    <div class="flex items-center gap-2 text-xs">
        <span class="bg-cyan-500/20 text-cyan-300 px-2 py-1 rounded">1. Nhận hàng từ khách</span>
        <span class="text-gray-600">→</span>
        <span class="bg-orange-500/20 text-orange-300 px-2 py-1 rounded">2. Bàn giao cho Shop</span>
    </div>
</div>

@if($returns->count() > 0)
<div class="space-y-4">
    @foreach($returns as $return)
    <div class="bg-gray-900 border {{ $return->status === 'approved_return' ? 'border-cyan-500/30' : 'border-gray-800' }} rounded-xl p-5 transition">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-3 flex-wrap">
            <span class="text-white font-bold font-mono">{{ $return->order->order_number ?? 'N/A' }}</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $return->status_color }}">
                {{ $return->status_icon }} {{ $return->status_label }}
            </span>
        </div>

        {{-- Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-400 mb-4">
            <div>👤 <span class="text-gray-200">{{ $return->user->name ?? 'N/A' }}</span></div>
            <div>📞 <span class="text-cyan-400 font-bold">{{ $return->order->shipping_phone ?? '—' }}</span></div>
            <div class="truncate">📍 {{ $return->order->shipping_address ?? '—' }}</div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <a href="{{ route('shipper.returns.show', $return) }}"
               class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Chi tiết
            </a>

            @if($return->status === 'approved_return')
            <form action="{{ route('shipper.returns.pickup', $return) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" onclick="return confirm('Xác nhận đã lấy hàng từ khách?')"
                    class="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    🚚 Xác nhận đã lấy hàng
                </button>
            </form>

            @elseif($return->status === 'shipper_returning')
            <form action="{{ route('shipper.returns.delivered', $return) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" onclick="return confirm('Xác nhận đã bàn giao hàng về shop?')"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    📦 Đã giao hàng về shop
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $returns->links('pagination::tailwind') }}</div>
@else
<div class="text-center py-20 bg-gray-900 rounded-xl border border-gray-800">
    <div class="text-5xl mb-4">📭</div>
    <p class="text-gray-400">Không có đơn hoàn hàng nào.</p>
</div>
@endif

@endsection