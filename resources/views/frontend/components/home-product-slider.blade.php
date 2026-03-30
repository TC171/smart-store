<section x-data="productSlider()" class="relative bg-white rounded-3xl p-5 md:p-6 shadow-xl border border-gray-100">
    <div class="flex items-start justify-between gap-4 mb-5">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
            @isset($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endisset
        </div>

        <a href="{{ $viewMoreUrl }}"
           class="hidden md:inline-flex shrink-0 items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 text-orange-500 font-semibold hover:bg-orange-50 transition">
            Xem thêm
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if($products->count())
        <div class="relative">
            @if($products->count() > 4)
                <button type="button" @click="scrollLeft()"
                    class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white shadow-lg border border-gray-200 hover:bg-orange-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button type="button" @click="scrollRight()"
                    class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white shadow-lg border border-gray-200 hover:bg-orange-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif

            <div x-ref="slider"
                 class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 no-scrollbar">
                @foreach($products as $product)
                    <div class="min-w-[78%] sm:min-w-[48%] md:min-w-[31%] lg:min-w-[23.5%] snap-start flex-shrink-0">
                        @include('frontend.components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
            {{ $emptyText ?? 'Chưa có sản phẩm' }}
        </div>
    @endif

    <div class="mt-5 text-center md:hidden">
        <a href="{{ $viewMoreUrl }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
            {{ $mobileButtonText ?? 'Xem thêm sản phẩm' }}
        </a>
    </div>
</section>