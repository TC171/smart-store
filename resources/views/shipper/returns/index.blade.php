@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">🔄 Đơn hoàn hàng</h1>
    <span class="text-gray-400 text-sm">Chỉ xem — Admin xử lý</span>
</div>

{{-- Thông báo quyền --}}
<div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl px-4 py-3 mb-6 flex items-start gap-3">
    <span class="text-yellow-400 text-lg mt-0.5">⚠️</span>
    <div class="text-sm text-yellow-300">
        <span class="font-semibold">Lưu ý:</span> Đây là các đơn hoàn hàng từ khách hàng sau khi bạn đã giao.
        Trạng thái do <span class="font-semibold">Admin</span> quản lý — bạn không thể thay đổi.
    </div>
</div>

@php
    $statusColors = [
        'pending'         => 'bg-yellow-500/20 text-yellow-400',
        'approved_return' => 'bg-blue-500/20 text-blue-400',
        'refunded'        => 'bg-green-500/20 text-green-400',
        'rejected'        => 'bg-red-500/20 text-red-400',
    ];
    $statusLabels = [
        'pending'         => '⏳ Chờ duyệt',
        'approved_return' => '📦 Chờ gửi hàng về',
        'refunded'        => '✅ Đã hoàn hàng',
        'rejected'        => '❌ Từ chối',
    ];
    $typeLabels = [
        'refund' => '💰 Hoàn tiền',
        'return' => '📦 Đổi/Trả hàng',
    ];
@endphp

@if($returns->count() > 0)
<div class="space-y-3">
    @foreach($returns as $return)
    <div class="bg-gray-900 border border-gray-800 hover:border-gray-600 rounded-xl p-5 transition">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <span class="text-white font-bold">{{ $return->order->order_number ?? 'N/A' }}</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$return->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                        {{ $statusLabels[$return->status] ?? $return->status }}
                    </span>
                    <span class="text-gray-500 text-xs">{{ $typeLabels[$return->type] ?? $return->type }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5 text-sm text-gray-400">
                    <div>👤 <span class="text-gray-200">{{ $return->user->name ?? 'N/A' }}</span></div>
                    <div>📅 <span class="text-gray-300">{{ $return->created_at->format('d/m/Y H:i') }}</span></div>
                    <div class="md:col-span-2 truncate">📝 {{ Str::limit($return->reason, 80) }}</div>
                    @if($return->return_code)
                        <div class="md:col-span-2">
                            🏷️ Mã trả: <span class="text-cyan-400 font-mono font-medium">{{ $return->return_code }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('shipper.returns.show', $return) }}"
                   class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $returns->links('pagination::tailwind') }}</div>
@else
<div class="text-center py-16 bg-gray-900 rounded-xl border border-gray-800">
    <div class="text-5xl mb-4">✅</div>
    <p class="text-gray-400">Không có đơn hoàn hàng nào liên quan đến bạn.</p>
</div>
@endif

@endsection