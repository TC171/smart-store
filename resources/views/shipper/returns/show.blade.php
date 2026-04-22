@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('shipper.returns.index') }}" class="text-gray-400 hover:text-white transition">← Đơn hoàn hàng</a>
    <span class="text-gray-600">/</span>
    <h1 class="text-xl font-bold text-white">{{ $return->order->order_number ?? 'Chi tiết' }}</h1>
</div>

{{-- Banner read-only --}}
<div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
    <span class="text-yellow-400 text-xl">🔒</span>
    <p class="text-yellow-300 text-sm">
        Đơn hoàn hàng này do <strong>Admin</strong> xử lý. Bạn chỉ có thể xem thông tin, không thể thay đổi trạng thái.
    </p>
</div>

@php
    $statusColors = [
        'pending'         => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
        'approved_return' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
        'refunded'        => 'bg-green-500/20 text-green-400 border-green-500/30',
        'rejected'        => 'bg-red-500/20 text-red-400 border-red-500/30',
    ];
    $statusLabels = [
        'pending'         => '⏳ Chờ Admin duyệt',
        'approved_return' => '📦 Đã duyệt — Chờ khách gửi hàng về',
        'refunded'        => '✅ Đã hoàn hàng thành công',
        'rejected'        => '❌ Đã từ chối',
    ];
    $statusColor = $statusColors[$return->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
    $statusLabel = $statusLabels[$return->status] ?? $return->status;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Thông tin hoàn hàng --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Trạng thái (chỉ hiển thị, không có action) --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">Trạng thái yêu cầu</h2>
            <div class="flex items-center gap-3 p-4 rounded-xl border {{ $statusColor }}">
                <div class="text-2xl">
                    @if($return->status === 'pending') ⏳
                    @elseif($return->status === 'approved_return') 📦
                    @elseif($return->status === 'refunded') ✅
                    @else ❌
                    @endif
                </div>
                <div>
                    <div class="font-semibold">{{ $statusLabel }}</div>
                    @if($return->reviewed_at)
                        <div class="text-xs opacity-70 mt-0.5">Cập nhật lúc {{ $return->reviewed_at->format('H:i, d/m/Y') }}</div>
                    @endif
                </div>
            </div>

            @if($return->return_code)
            <div class="mt-4 p-3 bg-gray-800 rounded-lg">
                <div class="text-gray-400 text-xs mb-1">Mã trả hàng</div>
                <div class="font-mono text-cyan-400 font-bold text-lg tracking-wider">{{ $return->return_code }}</div>
                <div class="text-gray-500 text-xs mt-1">Khách dùng mã này để gửi hàng về</div>
            </div>
            @endif
        </div>

        {{-- Lý do hoàn hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3">
                📝 Lý do hoàn hàng
                <span class="text-xs font-normal text-gray-400 ml-2">
                    ({{ $return->type === 'return' ? 'Đổi/Trả hàng' : 'Hoàn tiền' }})
                </span>
            </h2>
            <p class="text-gray-300 bg-gray-800 rounded-lg p-4 text-sm leading-relaxed">{{ $return->reason }}</p>

            @if($return->admin_note)
            <div class="mt-3">
                <div class="text-gray-400 text-xs mb-1">Ghi chú từ Admin</div>
                <p class="text-gray-300 bg-blue-500/10 border border-blue-500/20 rounded-lg p-3 text-sm">{{ $return->admin_note }}</p>
            </div>
            @endif
        </div>

        {{-- Video bằng chứng (nếu có) --}}
        @if($return->video_path)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-3">🎥 Video bằng chứng</h2>
            <video controls class="w-full rounded-lg max-h-80 bg-black">
                <source src="{{ Storage::url($return->video_path) }}" type="video/mp4">
                Trình duyệt không hỗ trợ video.
            </video>
            @if($return->video_original_name)
                <p class="text-gray-500 text-xs mt-2">{{ $return->video_original_name }}</p>
            @endif
        </div>
        @endif

        {{-- Sản phẩm trong đơn --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">🛍️ Sản phẩm trong đơn</h2>
            <div class="space-y-3">
                @foreach($return->order->items as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ $item->product_name }}</div>
                        @if($item->variant)
                            <div class="text-gray-400 text-xs">{{ $item->variant->name ?? '' }}</div>
                        @endif
                        <div class="text-gray-500 text-xs">SKU: {{ $item->sku }}</div>
                    </div>
                    <div class="text-right text-sm shrink-0">
                        <div class="text-gray-300">x{{ $item->quantity }}</div>
                        <div class="text-cyan-400 font-medium">{{ number_format($item->price * $item->quantity) }}₫</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- RIGHT: Thông tin khách & đơn --}}
    <div class="space-y-5">

        {{-- Thông tin khách hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">👤 Khách hàng</h2>
            <div class="space-y-2 text-sm">
                <div>
                    <div class="text-gray-400 text-xs mb-0.5">Tên</div>
                    <div class="text-white">{{ $return->user->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-0.5">Email</div>
                    <div class="text-gray-300">{{ $return->user->email ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        {{-- Thông tin đơn hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">📋 Thông tin đơn</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Mã đơn</span>
                    <span class="text-white font-medium">{{ $return->order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Tổng tiền</span>
                    <span class="text-cyan-400 font-bold">{{ number_format($return->order->total_amount) }}₫</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Ngày yêu cầu</span>
                    <span class="text-gray-300">{{ $return->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Thông tin giao hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">📍 Địa chỉ giao</h2>
            <div class="text-sm text-gray-300 space-y-1">
                <div class="font-medium text-white">{{ $return->order->shipping_name }}</div>
                <div>{{ $return->order->shipping_phone }}</div>
                <div class="text-gray-400">
                    {{ collect([$return->order->shipping_address, $return->order->shipping_district, $return->order->shipping_city])->filter()->implode(', ') }}
                </div>
            </div>
        </div>

        {{-- Hướng dẫn cho shipper --}}
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
            <div class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-2">ℹ️ Lưu ý</div>
            <ul class="text-gray-400 text-xs space-y-1.5">
                <li>• Trạng thái hoàn hàng do Admin quyết định</li>
                <li>• Bạn không cần thực hiện thêm hành động nào</li>
                <li>• Nếu cần hỗ trợ, liên hệ Admin</li>
            </ul>
        </div>

    </div>
</div>

@endsection