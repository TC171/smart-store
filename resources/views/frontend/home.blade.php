@extends('frontend.layouts.app')

@section('title', 'Smart Store - Trang chủ')

@section('content')

<!-- AOS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-10 py-8 bg-gradient-to-b from-gray-50 to-white">

{{-- ================= HERO ================= --}}
@if($banners->count())
<div
    x-data="{ active: 0, paused: false }"
    x-init="setInterval(() => { if (!paused) active = (active + 1) % {{ $banners->count() }} }, 4000)"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    class="relative h-96 md:h-[500px] rounded-3xl overflow-hidden shadow-2xl"
    data-aos="fade-down"
>

    @foreach($banners as $index => $banner)
        <img src="{{ url('/storage/'.$banner->image) }}"
             class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
             :class="active === {{ $index }} ? 'opacity-100 scale-100' : 'opacity-0 scale-110'"
             loading="lazy">
    @endforeach

    <div class="absolute inset-0 bg-black/50"></div>

    {{-- Content --}}
    <div class="absolute bottom-10 left-10 text-white z-10 max-w-xl" data-aos="fade-up">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Siêu Sale <span class="text-orange-400">50%</span>
        </h1>
        <p class="mb-6 text-lg opacity-90">
            Mua ngay hôm nay để nhận ưu đãi lớn
        </p>
        <a href="{{ route('products.featured') }}"
           class="bg-orange-500 px-6 py-3 rounded-xl font-bold hover:bg-orange-600">
            Mua ngay
        </a>
    </div>

</div>
@endif


{{-- ================= CATEGORY ================= --}}
<section data-aos="fade-up">
    <div class="bg-white rounded-3xl p-6 shadow-xl">

        <h2 class="text-2xl font-bold mb-6">Danh mục nổi bật</h2>

        <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-8 gap-4">

            @forelse($featuredCategories as $cat)

                <a href="{{ route('category.products', $cat->slug) }}"
                   class="text-center group">

                    <div class="w-20 h-20 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-orange-100 transition">
                        @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}"
                                 class="w-12 h-12 object-cover rounded-lg">
                        @else
                            <span class="font-bold text-gray-600">
                                {{ strtoupper(substr($cat->name,0,2)) }}
                            </span>
                        @endif
                    </div>

                    <p class="text-sm mt-2 font-semibold">{{ $cat->name }}</p>

                </a>

            @empty
                <p>Không có danh mục</p>
            @endforelse

        </div>
    </div>
</section>


{{-- ================= PRODUCTS ================= --}}
<section data-aos="fade-up">
    <h2 class="text-2xl font-bold mb-4">🔥 Sản phẩm nổi bật</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

        @forelse($featuredProducts as $product)

            @include('frontend.components.product-card', ['product' => $product])

        @empty
            <p>Chưa có sản phẩm</p>
        @endforelse

    </div>
</section>


{{-- ================= COUPONS ================= --}}
<section data-aos="fade-up">
    <h2 class="text-2xl font-bold mb-4">🎁 Mã giảm giá</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

        @foreach($coupons as $coupon)

            <div class="p-4 bg-white rounded-xl shadow cursor-pointer"
                 x-data
                 @click="navigator.clipboard.writeText('{{ $coupon->code }}')">

                <p class="font-bold text-lg text-orange-500">
                    {{ $coupon->code }}
                </p>

                <p class="text-sm">
                    -{{ $coupon->type == 'percent' ? $coupon->value.'%' : number_format($coupon->value).'đ' }}
                </p>

            </div>

        @endforeach

    </div>
</section>


{{-- ================= NEWSLETTER ================= --}}
<section data-aos="fade-up">
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-10 rounded-3xl text-center">

        <h2 class="text-3xl font-bold mb-4">Đăng ký nhận ưu đãi</h2>

        <form class="flex gap-4 justify-center">
            <input type="email" placeholder="Email"
                   class="px-4 py-3 rounded-xl text-black w-1/2">

            <button class="bg-orange-500 px-6 py-3 rounded-xl font-bold">
                Đăng ký
            </button>
        </form>

    </div>
</section>

</div>

@endsection