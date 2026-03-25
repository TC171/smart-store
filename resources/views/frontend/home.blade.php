@extends('frontend.layouts.app')

@section('title', 'Smart Store - Trang chủ')

@section('content')

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init({
            duration: 800,
            once: true
        });
    });
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-10 py-8 bg-gradient-to-b from-gray-50 to-white">

    {{-- ================= HERO ================= --}}
    @if ($banners->count())
        <div x-data="{
                active: 0,
                paused: false,
                total: {{ $banners->count() }},
                prev() { this.active = (this.active - 1 + this.total) % this.total },
                next() { this.active = (this.active + 1) % this.total }
            }"
            x-init="setInterval(() => { if (!paused) next() }, 4000)"
            @mouseenter="paused = true" @mouseleave="paused = false"
            class="relative h-96 md:h-[500px] rounded-3xl overflow-hidden shadow-2xl group"
            data-aos="fade-down">

            @foreach ($banners as $index => $banner)
                <img src="{{ url('/storage/' . $banner->image) }}"
                    class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
                    :class="active === {{ $index }} ? 'opacity-100 scale-100' : 'opacity-0 scale-110'"
                    loading="lazy">
            @endforeach

            {{-- Prev --}}
            <button @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 flex items-center justify-center rounded-full bg-white shadow-lg hover:bg-orange-500 hover:text-white transition">
                ←
            </button>

            {{-- Next --}}
            <button @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 flex items-center justify-center rounded-full bg-white shadow-lg hover:bg-orange-500 hover:text-white transition">
                →
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2">
                @foreach ($banners as $index => $banner)
                    <button @click="active = {{ $index }}"
                        class="h-3 rounded-full"
                        :class="active === {{ $index }} ? 'bg-orange-500 w-8' : 'bg-white w-3'">
                    </button>
                @endforeach
            </div>
        </div>
    @endif


    {{-- ================= CATEGORY ================= --}}
    <section data-aos="fade-up">
        <div class="bg-white rounded-3xl p-6 shadow-xl">
            <h2 class="text-2xl font-bold mb-6">Danh mục nổi bật</h2>

            <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-8 gap-4">
                @forelse($featuredCategories as $cat)
                    <a href="{{ route('category.products', $cat->slug) }}" class="text-center group">

                        <div class="w-20 h-20 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-orange-100 transition">
                            @if ($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}"
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <span class="font-bold text-gray-600">
                                    {{ strtoupper(substr($cat->name, 0, 2)) }}
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


    {{-- ================= COUPONS ================= --}}
    <section data-aos="fade-up">
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100">

            <h2 class="text-2xl font-bold mb-6">🎁 Mã giảm giá nổi bật</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse ($coupons as $coupon)
                    <div x-data="{ copied: false }"
                         class="rounded-3xl border bg-orange-50 p-5 shadow hover:shadow-lg transition">

                        <h3 class="text-xl font-bold mb-2">{{ $coupon->code }}</h3>

                        <p class="text-orange-500 font-semibold mb-3">
                            @if ($coupon->type == 'percent')
                                -{{ $coupon->value }}%
                            @else
                                -{{ number_format($coupon->value) }}đ
                            @endif
                        </p>

                        @if ($coupon->expires_at)
                            <p class="text-sm text-gray-500 mb-3">
                                Hạn: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                            </p>
                        @endif

                        <button
                            @click="
                                navigator.clipboard.writeText('{{ $coupon->code }}');
                                copied = true;
                                setTimeout(() => copied = false, 1500);
                            "
                            class="w-full bg-orange-500 text-white py-2 rounded-xl font-bold">

                            <span x-show="!copied">Sao chép</span>
                            <span x-show="copied">Đã copy</span>
                        </button>
                    </div>
                @empty
                    <p>Chưa có mã giảm giá</p>
                @endforelse
            </div>
        </div>
    </section>


    {{-- ================= PRODUCTS ================= --}}
    @include('frontend.components.home-product-slider', [
        'title' => '🔥 Sản phẩm nổi bật',
        'subtitle' => 'Những sản phẩm được quan tâm nhiều nhất',
        'products' => $featuredProducts,
        'viewMoreUrl' => route('products.featured'),
    ])


    {{-- ================= CATEGORY PRODUCTS ================= --}}
    <section class="space-y-10" data-aos="fade-up">

        @include('frontend.components.home-product-slider', [
            'title' => '📱 Điện thoại',
            'products' => $phoneProducts,
            'viewMoreUrl' => route('category.products', 'dien-thoai'),
        ])

        @include('frontend.components.home-product-slider', [
            'title' => '💻 Laptop',
            'products' => $laptopProducts,
            'viewMoreUrl' => route('category.products', 'laptop'),
        ])

        @include('frontend.components.home-product-slider', [
            'title' => '📟 Máy tính bảng',
            'products' => $tabletProducts,
            'viewMoreUrl' => route('category.products', 'may-tinh-bang'),
        ])

        @include('frontend.components.home-product-slider', [
            'title' => '🎧 Phụ kiện',
            'products' => $accessoryProducts,
            'viewMoreUrl' => route('category.products', 'phu-kien'),
        ])

    </section>


    {{-- ================= NEWSLETTER ================= --}}
    <section data-aos="fade-up">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-10 rounded-3xl text-center">
            <h2 class="text-3xl font-bold mb-4">Đăng ký nhận ưu đãi</h2>

            <form class="flex gap-4 justify-center">
                <input type="email" placeholder="Email" class="px-4 py-3 rounded-xl text-black w-1/2">
                <button class="bg-orange-500 px-6 py-3 rounded-xl font-bold">
                    Đăng ký
                </button>
            </form>
        </div>
    </section>

</div>

@endsection