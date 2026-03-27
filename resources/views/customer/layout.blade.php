@extends('frontend.layouts.app')
@section('title', 'Customer Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Chào mừng, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-2">Quản lý tài khoản và đơn hàng của bạn</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Thành viên từ</p>
                    <p class="text-lg font-semibold text-gray-800">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tổng đơn hàng</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $customerTotalOrders ?? auth()->user()->orders()->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Đơn hoàn thành</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $customerCompletedOrders ?? auth()->user()->orders()->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tổng chi tiêu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($customerTotalSpent ?? auth()->user()->orders()->sum('total_amount')) }}₫</p>
                    </div>
                </div>
            </div>
        </div>

        @yield('customer-content')
    </div>
</div>
@endsection