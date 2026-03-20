@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

<div class="max-w-7xl mx-auto px-4 mt-6 space-y-6">

    <!-- ===== DANH MỤC ===== -->
    <div class="bg-white p-4 rounded-xl shadow">
        <div class="grid grid-cols-5 md:grid-cols-10 gap-4 text-center">

            @foreach($featuredCategories as $cat)
                <div class="hover:scale-105 transition cursor-pointer">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full"></div>
                    <p class="text-xs mt-2">{{ $cat->name }}</p>
                </div>
            @endforeach

        </div>
    </div>

    <!-- ===== BANNER SLIDER ===== -->
    @if($banners->count())
    <div x-data="{ active: 0 }"
         x-init="setInterval(() => active = (active + 1) % {{ $banners->count() }}, 3000)"
         class="relative">

        <div class="relative overflow-hidden rounded-xl shadow">

            @foreach($banners as $index => $banner)
                <img src="{{ asset('storage/' . $banner->image) }}"
                     x-show="active === {{ $index }}"
                     x-transition
                     class="w-full h-[300px] object-cover">
            @endforeach

            <!-- Dots -->
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                @foreach($banners as $index => $banner)
                    <div @click="active = {{ $index }}"
                         :class="active === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                         class="w-2 h-2 rounded-full cursor-pointer">
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif

    <!-- ===== SẢN PHẨM NỔI BẬT ===== -->
    <div>
        <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
            🔥 <span class="border-l-4 border-orange-500 pl-2">Sản phẩm nổi bật</span>
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

            @forelse($featuredProducts as $product)
                @include('frontend.components.product-card', compact('product'))
            @empty
                <p>Chưa có sản phẩm</p>
            @endforelse

        </div>
    </div>

</div>

@endsection
