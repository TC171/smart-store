@extends('frontend.layouts.app')

@section('title', 'Smart Store - Trang chủ')

@section('content')

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
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
                class="relative h-96 md:h-[500px] rounded-3xl overflow-hidden shadow-2xl group" data-aos="fade-down">

                @foreach ($banners as $index => $banner)
                    <img src="{{ url('/storage/' . $banner->image) }}"
                        class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
                        :class="active === {{ $index }} ? 'opacity-100 scale-100' : 'opacity-0 scale-110'"
                        loading="lazy">
                @endforeach


                {{-- Nút mũi tên TRÁI --}}
                <button @click="prev()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 flex items-center justify-center rounded-full bg-white shadow-lg text-gray-800 hover:bg-orange-500 hover:text-white transition-all duration-300 hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Nút mũi tên PHẢI --}}
                <button @click="next()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 flex items-center justify-center rounded-full bg-white shadow-lg text-gray-800 hover:bg-orange-500 hover:text-white transition-all duration-300 hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Dot indicators --}}
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                    @foreach ($banners as $index => $banner)
                        <button @click="active = {{ $index }}"
                            class="h-3 rounded-full transition-all duration-300 shadow"
                            :class="active === {{ $index }} ? 'bg-orange-500 w-8' : 'bg-white w-3 hover:bg-orange-300'">
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

                            <div
                                class="w-20 h-20 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-orange-100 transition">
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
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <p class="text-orange-500 font-semibold text-sm uppercase tracking-wider">Ưu đãi hôm nay</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">🎁 Mã giảm giá nổi bật</h2>
                <p class="text-gray-500 mt-1">Sao chép mã để áp dụng khi thanh toán và tiết kiệm hơn cho đơn hàng của bạn.</p>
            </div>

            <div class="text-sm text-gray-400">
                Chọn mã phù hợp với đơn hàng của bạn
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse ($coupons as $coupon)
                <div
                    x-data="{ copied: false }"
                    class="group relative overflow-hidden rounded-3xl border border-orange-100 bg-gradient-to-br from-white via-orange-50/40 to-orange-100/40 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                >
                    {{-- Decor --}}
                    <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full bg-orange-200/30 blur-2xl"></div>
                    <div class="absolute top-1/2 -left-3 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-50 border border-gray-100"></div>
                    <div class="absolute top-1/2 -right-3 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-50 border border-gray-100"></div>

                    <div class="relative p-5">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-orange-500 mb-2">
                                    Mã ưu đãi
                                </p>
                                <h3 class="text-2xl font-extrabold text-gray-900 tracking-wide">
                                    {{ $coupon->code }}
                                </h3>
                            </div>

                            <div class="shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-500 text-white text-sm font-bold shadow">
                                    @if ($coupon->type == 'percent')
                                        -{{ $coupon->value }}%
                                    @else
                                        -{{ number_format($coupon->value) }}đ
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-orange-200 my-4"></div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <p class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                                Áp dụng nhanh khi thanh toán
                            </p>

                         

                            @if ($coupon->expires_at)
                                <p class="flex items-center gap-2 text-gray-500">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                    Áp dụng đến:
                                    <span class="font-semibold text-gray-700">
                                        {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                                    </span>
                                </p>
                            @endif
                        </div>

                        <div class="mt-5 flex items-center gap-3">
                            <button
                                type="button"
                                @click="
                                    navigator.clipboard.writeText('{{ $coupon->code }}');
                                    copied = true;
                                    setTimeout(() => copied = false, 1800);
                                "
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-orange-500 px-4 py-3 text-white font-bold hover:bg-orange-600 active:scale-[0.98] transition"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16h8M8 12h8m-8-4h8M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>
                                <span x-show="!copied">Sao chép mã</span>
                                <span x-show="copied" x-transition>Đã sao chép</span>
                            </button>

                            <div class="px-3 py-3 rounded-2xl bg-white border border-orange-100 text-orange-500 font-bold shadow-sm">
                                Deal
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="rounded-3xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center">
                        <p class="text-lg font-semibold text-gray-700">Hiện chưa có mã giảm giá</p>
                        <p class="text-sm text-gray-500 mt-1">Ưu đãi mới sẽ sớm được cập nhật.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
  
        {{-- ================= PRODUCTS ================= --}}
        <section data-aos="fade-up" x-data="productSlider()" class="relative">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold">🔥 Sản phẩm nổi bật</h2>
                    <p class="text-gray-500 text-sm">Những sản phẩm được quan tâm nhiều nhất</p>
                </div>

                <a href="{{ route('products.featured') }}"
                    class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
                    Xem thêm
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="relative">
                {{-- Nút trái --}}
                <button @click="scrollLeft()"
                    class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white shadow-lg border hover:bg-orange-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Nút phải --}}
                <button @click="scrollRight()"
                    class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white shadow-lg border hover:bg-orange-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Danh sách sản phẩm --}}
                <div x-ref="slider"
                    class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 no-scrollbar">
                    @forelse($featuredProducts as $product)
                        <div class="min-w-[78%] sm:min-w-[48%] md:min-w-[31%] lg:min-w-[23.5%] snap-start flex-shrink-0">
                            @include('frontend.components.product-card', ['product' => $product])
                        </div>
                    @empty
                        <p>Chưa có sản phẩm</p>
                    @endforelse
                </div>
            </div>

            {{-- Nút xem thêm mobile --}}
            <div class="mt-5 text-center md:hidden">
                <a href="{{ route('products.featured') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                    Xem thêm sản phẩm
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </section>

        <script>
            function productSlider() {
                return {
                    scrollAmount: 320,
                    scrollLeft() {
                        this.$refs.slider.scrollBy({
                            left: -this.scrollAmount,
                            behavior: 'smooth'
                        });
                    },
                    scrollRight() {
                        this.$refs.slider.scrollBy({
                            left: this.scrollAmount,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        </script>

        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>


    {{-- ================= CATEGORY PRODUCT SECTIONS ================= --}}
<section class="space-y-10" data-aos="fade-up">

    {{-- Điện thoại --}}
    <div class="bg-white rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold">📱 Điện thoại</h2>
                <p class="text-sm text-gray-500">Những mẫu điện thoại đáng chú ý dành cho bạn</p>
            </div>

            <a href="{{ route('category.products', 'dien-thoai') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
                Xem thêm
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @forelse($phoneProducts as $product)
                @include('frontend.components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-gray-500">Chưa có sản phẩm điện thoại</p>
            @endforelse
        </div>
    </div>

    {{-- Laptop --}}
    <div class="bg-white rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold">💻 Laptop</h2>
                <p class="text-sm text-gray-500">Các dòng laptop nổi bật cho học tập và công việc</p>
            </div>

            <a href="{{ route('category.products', 'laptop') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
                Xem thêm
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @forelse($laptopProducts as $product)
                @include('frontend.components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-gray-500">Chưa có sản phẩm laptop</p>
            @endforelse
        </div>
    </div>

    {{-- Máy tính bảng --}}
    <div class="bg-white rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold">📟 Máy tính bảng</h2>
                <p class="text-sm text-gray-500">Thiết bị gọn nhẹ cho học tập, giải trí và làm việc</p>
            </div>

            <a href="{{ route('category.products', 'may-tinh-bang') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
                Xem thêm
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @forelse($tabletProducts as $product)
                @include('frontend.components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-gray-500">Chưa có sản phẩm máy tính bảng</p>
            @endforelse
        </div>
    </div>

    {{-- Phụ kiện --}}
    <div class="bg-white rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold">🎧 Phụ kiện</h2>
                <p class="text-sm text-gray-500">Tai nghe, sạc, cáp và các phụ kiện cần thiết</p>
            </div>

            <a href="{{ route('category.products', 'phu-kien') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
                Xem thêm
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @forelse($accessoryProducts as $product)
                @include('frontend.components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-gray-500">Chưa có sản phẩm phụ kiện</p>
            @endforelse
        </div>
    </div>

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
