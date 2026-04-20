@extends('frontend.layouts.app')

@section('title', 'Dashboard Giao Hàng - Smart Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Giao Hàng</h1>
                <p class="text-gray-600 mt-1">Chào mừng, {{ auth()->user()->name }}!</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('delivery.orders.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Xem Đơn Hàng
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Đăng Xuất
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Thống kê đơn hàng -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Đơn hàng đang giao</p>
                        <p class="text-2xl font-bold">{{ $stats['delivering'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Đơn hàng đã giao</p>
                        <p class="text-2xl font-bold">{{ $stats['delivered'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100">Đơn hàng chờ lấy</p>
                        <p class="text-2xl font-bold">{{ $stats['assigned'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Đơn hàng gần đây</h2>
            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">Đơn hàng #{{ $order->id }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->user->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->delivery_status === 'assigned') bg-yellow-100 text-yellow-800
                                        @elseif($order->delivery_status === 'picked_up') bg-blue-100 text-blue-800
                                        @elseif($order->delivery_status === 'delivering') bg-purple-100 text-purple-800
                                        @elseif($order->delivery_status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->delivery_status === 'failed') bg-red-100 text-red-800
                                        @elseif($order->delivery_status === 'returned') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'unknown')) }}
                                    </span>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ number_format($order->total_amount) }}đ</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Chưa có đơn hàng nào</p>
            @endif
        </div>
    </div>
</div>
@endsection