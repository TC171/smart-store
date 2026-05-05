@extends('frontend.layouts.app')
@section('title', 'Danh sách yêu thích - Smart Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 mt-20">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span class="text-red-500">❤️</span> Sản phẩm yêu thích
            </h1>
            <p class="text-gray-500 mt-1 text-sm">{{ $wishlists->total() }} sản phẩm trong danh sách của bạn</p>
        </div>
        <a href="/" class="text-sm font-medium text-orange-500 hover:text-orange-600 transition flex items-center gap-1">
            ← Tiếp tục mua sắm
        </a>
    </div>

    @if($wishlists->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($wishlists as $item)
        @php
            $product   = $item->product;
            $catSlug   = $product->category?->slug
                      ?? \App\Models\Category::where('id', $product->category_id)->value('slug')
                      ?? 'san-pham';
            $productUrl = route('products.show', [$catSlug, $product->slug]);
            // Lấy ảnh giống hệt product-card: thumbnail -> images -> fallback
            if ($product->thumbnail) {
                $image = asset('storage/' . $product->thumbnail);
            } elseif ($product->images->count() > 0) {
                $image = asset('storage/' . $product->images->first()->image);
            } else {
                $image = asset('images/no-image.jpg');
            }
            $price     = $product->variants->where('status', 1)->min('sale_price')
                      ?? $product->variants->where('status', 1)->min('price') ?? 0;
            $origPrice = $product->variants->where('status', 1)->min('price') ?? 0;
            $discount  = ($origPrice > $price && $origPrice > 0) ? round((1 - $price / $origPrice) * 100) : 0;
        @endphp
        <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

            {{-- Image --}}
            <a href="{{ $productUrl }}" class="block relative overflow-hidden bg-gray-50 aspect-square">
                <img src="{{ $image }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @if($discount > 0)
                <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">-{{ $discount }}%</span>
                @endif
            </a>

            {{-- Remove button (heart filled) --}}
            <form method="POST" action="{{ route('customer.wishlist.remove', $product) }}" class="absolute top-2 right-2">
                @csrf
                <button type="submit"
                    class="w-9 h-9 rounded-full bg-white shadow-md flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 group/heart">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </form>

            {{-- Info --}}
            <div class="p-3">
                <a href="{{ $productUrl }}" class="block text-sm font-semibold text-gray-800 line-clamp-2 hover:text-orange-500 transition mb-2">
                    {{ $product->name }}
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-orange-500 font-black text-base">{{ number_format($price) }}₫</span>
                        @if($discount > 0)
                        <span class="text-gray-400 text-xs line-through ml-1">{{ number_format($origPrice) }}₫</span>
                        @endif
                    </div>
                </div>

                {{-- Add to cart --}}
                <a href="{{ $productUrl }}"
                   class="mt-3 w-full block text-center bg-orange-500 hover:bg-orange-600 active:scale-[.98] text-white text-xs font-bold py-2 rounded-xl transition-all duration-200">
                    Thêm vào giỏ
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $wishlists->links() }}
    </div>

    @else
    <div class="text-center py-24">
        <div class="text-7xl mb-5">💔</div>
        <h2 class="text-2xl font-black text-gray-800 mb-2">Chưa có sản phẩm yêu thích</h2>
        <p class="text-gray-500 mb-8">Hãy bấm vào biểu tượng ❤️ trên sản phẩm để thêm vào danh sách yêu thích nhé!</p>
        <a href="/" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-3 rounded-2xl transition shadow-lg shadow-orange-200">
            Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>
@endsection
