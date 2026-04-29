@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">🔄 Danh sách yêu cầu</h1>
        <span class="bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full text-sm font-semibold">
            {{ $refunds->total() }} yêu cầu
        </span>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending"           {{ request('status') === 'pending'           ? 'selected' : '' }}>⏳ Chờ xét duyệt</option>
                <option value="approved_return"   {{ request('status') === 'approved_return'   ? 'selected' : '' }}>📋 Chờ shipper lấy hàng</option>
                <option value="shipper_picking"   {{ request('status') === 'shipper_picking'   ? 'selected' : '' }}>🚚 Shipper đang lấy hàng</option>
                <option value="shipper_returning" {{ request('status') === 'shipper_returning' ? 'selected' : '' }}>🔄 Đang chuyển về shop</option>
                <option value="goods_received"    {{ request('status') === 'goods_received'    ? 'selected' : '' }}>📦 Hàng đã về shop</option>
                <option value="refunded"          {{ request('status') === 'refunded'          ? 'selected' : '' }}>✅ Đã hoàn tiền</option>
                <option value="rejected"          {{ request('status') === 'rejected'          ? 'selected' : '' }}>❌ Đã từ chối</option>
            </select>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Lọc</button>
        </form>
    </div>

    @if($refunds->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Khách hàng</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Đơn hàng</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Loại</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Video</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Ngày gửi</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refunds as $refund)
                <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                    <td class="px-5 py-4">
                        <p class="text-white font-medium text-sm">{{ $refund->user->name ?? 'N/A' }}</p>
                        <p class="text-gray-400 text-xs">{{ $refund->user->email ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4 text-cyan-400 font-medium text-sm">
                        <a href="{{ route('admin.orders.show', $refund->order_id) }}" class="hover:text-cyan-300 hover:underline">
                            #{{ $refund->order->order_number ?? $refund->order_id }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <span class="{{ $refund->type === 'return' ? 'bg-orange-500/20 text-orange-400' : 'bg-green-500/20 text-green-400' }} px-2 py-1 rounded-full text-xs font-bold">
                            {{ $refund->type_label }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($refund->video_path)
                        <span class="text-green-400 text-xs flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.876V15.124a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Có video
                        </span>
                        @else
                        <span class="text-gray-500 text-xs">Không có</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @php
                        $statusMap = [
                            'pending'           => ['bg-yellow-500/20 text-yellow-400', '⏳ Chờ duyệt'],
                            'approved_return'   => ['bg-blue-500/20 text-blue-400',     '📋 Chờ shipper'],
                            'shipper_picking'   => ['bg-cyan-500/20 text-cyan-400',     '🚚 Đang lấy hàng'],
                            'shipper_returning' => ['bg-indigo-500/20 text-indigo-400', '🔄 Đang về shop'],
                            'goods_received'    => ['bg-orange-500/20 text-orange-400', '📦 Hàng về shop'],
                            'refunded'          => ['bg-green-500/20 text-green-400',   '✅ Đã hoàn tiền'],
                            'rejected'          => ['bg-red-500/20 text-red-400',       '❌ Từ chối'],
                        ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $statusMap[$refund->status][0] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $statusMap[$refund->status][1] ?? $refund->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-sm">
                        {{ $refund->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.refunds.show', $refund) }}"
                           class="text-orange-400 hover:text-orange-300 text-sm font-medium hover:underline">
                            Xem & Duyệt
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $refunds->links('pagination::tailwind') }}
    </div>
    @else
    <div class="text-center py-16 bg-gray-900 rounded-xl">
        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        </div>
        <p class="text-gray-400 text-lg">Chưa có yêu cầu nào</p>
    </div>
    @endif
</div>
@endsection