@extends('frontend.layouts.app')

@section('title', 'Sản phẩm nổi bật')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-10">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    🔥 Sản phẩm nổi bật
                </h1>
                <p class="text-gray-500 mt-2">
                    Khám phá những sản phẩm đang được quan tâm nhiều nhất tại Smart Store.
                </p>
            </div>

            {{-- Category Card --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                    Danh mục
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('products.featured', request()->except(['category', 'page'])) }}"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition
                   {{ !request('category') ? 'bg-orange-500 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-500' }}">
                        Tất cả
                    </a>

                    <a href="{{ route('products.featured', array_merge(request()->except('page'), ['category' => 'dien-thoai'])) }}"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition
                   {{ request('category') == 'dien-thoai' ? 'bg-orange-500 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-500' }}">
                        📱 Điện thoại
                    </a>

                    <a href="{{ route('products.featured', array_merge(request()->except('page'), ['category' => 'laptop'])) }}"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition
                   {{ request('category') == 'laptop' ? 'bg-orange-500 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-500' }}">
                        💻 Laptop
                    </a>

                    <a href="{{ route('products.featured', array_merge(request()->except('page'), ['category' => 'may-tinh-bang'])) }}"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition
                   {{ request('category') == 'may-tinh-bang' ? 'bg-orange-500 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-500' }}">
                        📟 Máy tính bảng
                    </a>

                    <a href="{{ route('products.featured', array_merge(request()->except('page'), ['category' => 'phu-kien'])) }}"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition
                   {{ request('category') == 'phu-kien' ? 'bg-orange-500 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-orange-500' }}">
                        🎧 Phụ kiện
                    </a>
                </div>
            </section>

            {{-- Brand Card --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
                <p class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                    Thương hiệu
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <a href="{{ route('products.featured', request()->except(['brand', 'page'])) }}"
                        class="h-14 flex items-center justify-center rounded-xl border text-sm font-semibold transition
           {{ !request('brand') ? 'bg-orange-500 text-white border-orange-500 shadow' : 'bg-white text-gray-700 border-gray-200 hover:border-orange-300 hover:text-orange-500' }}">
                        Tất cả
                    </a>

                    @foreach ($brands as $brand)
                        <a href="{{ route('products.featured', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}"
                            class="h-14 px-3 flex items-center justify-center rounded-xl border bg-white transition overflow-hidden
               {{ request('brand') == $brand->slug ? 'ring-2 ring-orange-500 border-orange-500 shadow-sm' : 'border-gray-200 hover:border-orange-300 hover:shadow-sm' }}"
                            title="{{ $brand->name }}">

                            @if (!empty($brand->logo))
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                    class="h-8 w-auto max-w-full object-contain">
                            @else
                                <span class="truncate text-sm font-semibold text-gray-700">
                                    {{ $brand->name }}
                                </span>
                            @endif

                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Main Layout --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

                {{-- Sidebar --}}
                <aside class="xl:col-span-3">
                    <form method="GET" action="{{ route('products.featured') }}"
                        class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-5 xl:sticky xl:top-24">

                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Bộ lọc</h3>
                            <a href="{{ route('products.featured') }}"
                                class="text-sm font-medium text-orange-500 hover:underline">
                                Reset
                            </a>
                        </div>

                        {{-- Price Range --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-800 mb-3">Mức giá</p>

                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="all"
                                        {{ !request('price_range') || request('price_range') == 'all' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Tất cả</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="under_2m"
                                        {{ request('price_range') == 'under_2m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Dưới 2 triệu</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="2m_4m"
                                        {{ request('price_range') == '2m_4m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Từ 2 - 4 triệu</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="4m_7m"
                                        {{ request('price_range') == '4m_7m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Từ 4 - 7 triệu</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="7m_13m"
                                        {{ request('price_range') == '7m_13m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Từ 7 - 13 triệu</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="13m_20m"
                                        {{ request('price_range') == '13m_20m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Từ 13 - 20 triệu</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="price_range" value="over_20m"
                                        {{ request('price_range') == 'over_20m' ? 'checked' : '' }}
                                        class="h-5 w-5 border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-base text-gray-800">Trên 20 triệu</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="submit"
                                class="flex-1 px-5 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                                Áp dụng
                            </button>

                            <a href="{{ route('products.featured', request()->except(['price_range', 'page'])) }}"
                                class="px-4 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                Xóa giá
                            </a>
                        </div>
                    </form>
                </aside>

                {{-- Main Content --}}
                <section class="xl:col-span-9">

                    {{-- Result Bar --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-3 mb-5">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                            <p class="text-sm text-gray-500">
                                Hiển thị
                                <span class="font-bold text-orange-500">{{ $products->firstItem() ?? 0 }}</span>
                                -
                                <span class="font-bold text-orange-500">{{ $products->lastItem() ?? 0 }}</span>
                                trong tổng
                                <span class="font-bold text-gray-900">{{ $products->total() }}</span>
                                sản phẩm
                            </p>

                            <form method="GET" action="{{ route('products.featured') }}"
                                class="flex items-center gap-3">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="brand" value="{{ request('brand') }}">
                                <input type="hidden" name="price_range" value="{{ request('price_range') }}">

                                <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Sắp xếp:</label>
                                <select name="sort" onchange="this.form.submit()"
                                    class="rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 min-w-[180px]">
                                    <option value="">Mặc định</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>
                                        Bán chạy</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá
                                        tăng dần</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá
                                        giảm dần</option>
                                </select>
                            </form>
                        </div>

                        @if (request('category') || request('brand') || request('price_range') || request('sort'))
                            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                                @if (request('category'))
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-50 text-orange-600 text-sm font-medium">
                                        {{ optional($categories->firstWhere('slug', request('category')))->name ?? request('category') }}
                                    </span>
                                @endif

                                @if (request('brand'))
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-50 text-orange-600 text-sm font-medium">
                                        {{ optional($brands->firstWhere('slug', request('brand')))->name ?? request('brand') }}
                                    </span>
                                @endif

                                @if (request('price_range') && request('price_range') !== 'all')
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-50 text-orange-600 text-sm font-medium">
                                        @switch(request('price_range'))
                                            @case('under_2m')
                                                Dưới 2 triệu
                                            @break

                                            @case('2m_4m')
                                                Từ 2 - 4 triệu
                                            @break

                                            @case('4m_7m')
                                                Từ 4 - 7 triệu
                                            @break

                                            @case('7m_13m')
                                                Từ 7 - 13 triệu
                                            @break

                                            @case('13m_20m')
                                                Từ 13 - 20 triệu
                                            @break

                                            @case('over_20m')
                                                Trên 20 triệu
                                            @break
                                        @endswitch
                                    </span>
                                @endif

                                @if (request('sort'))
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">
                                        @switch(request('sort'))
                                            @case('newest')
                                                Mới nhất
                                            @break

                                            @case('best_seller')
                                                Bán chạy
                                            @break

                                            @case('price_asc')
                                                Giá tăng dần
                                            @break

                                            @case('price_desc')
                                                Giá giảm dần
                                            @break

                                            @default
                                                Mặc định
                                        @endswitch
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Product Grid --}}
                    @if ($products->count())
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach ($products as $product)
                                @include('frontend.components.product-card', ['product' => $product])
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div class="mt-8 flex justify-center">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                            <div
                                class="w-20 h-20 mx-auto rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                                <span class="text-3xl">📦</span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-800">Không tìm thấy sản phẩm phù hợp</h3>
                            <p class="text-gray-500 mt-2 max-w-md mx-auto">
                                Hãy thử thay đổi bộ lọc hoặc bỏ bớt điều kiện để xem thêm nhiều sản phẩm hơn.
                            </p>

                            <a href="{{ route('products.featured') }}"
                                class="inline-flex items-center gap-2 mt-5 px-5 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                                Xem lại tất cả sản phẩm
                            </a>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
