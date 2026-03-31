@extends('frontend.layouts.app')

@section('title', $brand->name)

@section('content')

<div class="max-w-7xl mx-auto p-4 mb-16">

    <!-- Header Thương hiệu -->
    <div class="flex items-center gap-4 border-b border-gray-100 pb-6 mb-8 mt-4">
        @if($brand->logo)
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-16 w-32 object-contain bg-white rounded-xl p-3 shadow-sm border border-gray-100">
        @else
            <div class="h-16 w-16 flex items-center justify-center bg-gradient-to-br from-orange-100 to-amber-50 text-orange-600 rounded-xl text-2xl font-black shadow-sm border border-orange-100">
                {{ substr($brand->name, 0, 1) }}
            </div>
        @endif
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $brand->name }}</h1>
            <p class="text-gray-500 mt-1.5 text-sm font-medium">Khám phá các sản phẩm chính hãng từ {{ $brand->name }}</p>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4">
        @forelse($products as $product)
            @include('frontend.components.product-card', ['product' => $product])
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mb-5 text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Chưa có sản phẩm</h3>
                <p class="text-gray-500 max-w-sm mb-6">Hiện tại chưa có sản phẩm nào thuộc thương hiệu này được bán.</p>
                <a href="{{ route('home') }}" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-full font-bold transition-all shadow-sm">
                    Về trang chủ
                </a>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif

</div>

@endsection
