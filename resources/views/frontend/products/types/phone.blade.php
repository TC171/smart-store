@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-10">

    {{-- PRODUCT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- IMAGE --}}
        <div class="bg-white rounded-2xl shadow p-6">
            @if($product->thumbnail)
                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                     class="w-full h-[400px] object-contain rounded-xl">
            @endif
        </div>

        {{-- INFO --}}
        <div class="space-y-5">

            <h1 class="text-2xl font-bold text-gray-800">
                {{ $product->name }}
            </h1>

            <div class="text-gray-500 text-sm">
                {{ $product->category->name ?? '-' }} • {{ $product->brand->name ?? '-' }}
            </div>

            {{-- PRICE --}}
            <div class="bg-gray-100 p-4 rounded-xl">
                <span class="text-3xl text-red-500 font-bold">
                    {{ number_format($minPrice) }} ₫
                </span>
                <span class="text-gray-400 ml-2 line-through">
                    {{ number_format($maxPrice) }} ₫
                </span>
            </div>

            {{-- VARIANT --}}
            <div>
                <h3 class="font-semibold mb-2">Chọn phiên bản</h3>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($product->variants as $variant)
                        <label class="border rounded-xl p-3 cursor-pointer hover:border-black flex flex-col">
                            <input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden variant-radio">

                            <div class="font-semibold text-sm">
                                {{ $variant->color }} - {{ $variant->ram }} - {{ $variant->storage }}
                            </div>

                            <div class="text-xs text-gray-500">
                                Còn: {{ $variant->stock }}
                            </div>

                            <div class="text-red-500 font-bold text-sm">
                                {{ number_format($variant->sale_price ?: $variant->price) }} ₫
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex gap-4 mt-4">

                {{-- ADD TO CART --}}
                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this)">
                    @csrf
                    <input type="hidden" name="variant_id">

                    <button class="bg-black text-white px-6 py-3 rounded-xl hover:opacity-80 w-full">
                        🛒 Thêm vào giỏ
                    </button>
                </form>

                {{-- BUY NOW --}}
                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this)">
                    @csrf
                    <input type="hidden" name="variant_id">
                    <input type="hidden" name="buy_now" value="1">

                    <button class="bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 w-full">
                        Mua ngay
                    </button>
                </form>

            </div>

            {{-- SHORT DESC --}}
            <div>
                <h3 class="font-semibold mb-1">Mô tả</h3>
                <p class="text-gray-600 text-sm">
                    {{ $product->short_description }}
                </p>
            </div>

        </div>
    </div>

    {{-- COUPONS --}}
    <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-2xl">
        <h3 class="font-bold mb-4 text-yellow-700">🎁 Mã ưu đãi</h3>

        <div class="flex flex-wrap gap-3">
            @foreach($coupons as $coupon)
                <div class="flex items-center justify-between gap-3 bg-white border border-yellow-300 px-4 py-3 rounded-xl shadow-sm min-w-[200px]">

                    <div>
                        <div class="font-bold text-yellow-700">
                            {{ $coupon->code }}
                        </div>
                        <div class="text-xs text-gray-500">
                            @if($coupon->type === 'percent')
                                -{{ $coupon->value }}%
                            @else
                                -{{ number_format($coupon->value) }}₫
                            @endif
                        </div>
                    </div>

                    <button onclick="copyCoupon('{{ $coupon->code }}', this)"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded-lg">
                        Copy
                    </button>

                </div>
            @endforeach
        </div>
    </div>

    {{-- DESCRIPTION --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-bold mb-3">Mô tả chi tiết</h2>
        <p class="text-gray-700 whitespace-pre-line">
            {{ $product->description }}
        </p>
    </div>

    {{-- SPEC --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-bold mb-4">Thông số</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><span class="text-gray-500">Bảo hành</span><div class="font-semibold">{{ $product->warranty_months }} tháng</div></div>
            <div><span class="text-gray-500">Kích thước</span><div class="font-semibold">{{ $product->length }} x {{ $product->width }} x {{ $product->height }}</div></div>
            <div><span class="text-gray-500">Cân nặng</span><div class="font-semibold">{{ $product->weight }} kg</div></div>
            <div><span class="text-gray-500">Tồn kho</span><div class="font-semibold">{{ $totalStock }}</div></div>
        </div>
    </div>

    {{-- REVIEWS --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-bold mb-6">⭐ Đánh giá</h2>

        <div class="flex items-center gap-4 mb-6">
            <div class="text-4xl font-bold text-yellow-500">
                {{ $avgRating ?? 0 }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $totalReviews }} đánh giá
            </div>
        </div>

        @forelse($reviews as $review)
            <div class="border-b pb-4 mb-4">
                <div class="flex justify-between">
                    <span class="font-semibold">{{ $review->user->name ?? 'Khách' }}</span>
                    <span class="text-yellow-400">
                        {{ str_repeat('★', $review->rating) }}
                    </span>
                </div>

                <p class="text-gray-600 mt-2 text-sm">
                    {{ $review->comment }}
                </p>

                <div class="text-xs text-gray-400 mt-1">
                    {{ $review->created_at->format('d/m/Y') }}
                </div>
            </div>
        @empty
            <p class="text-gray-500">Chưa có đánh giá</p>
        @endforelse
    </div>

</div>
@endsection

{{-- SCRIPT --}}
<script>
function copyCoupon(code, btn) {
    navigator.clipboard.writeText(code);
    btn.innerText = "✓";
    btn.classList.add('bg-green-500');

    setTimeout(() => {
        btn.innerText = "Copy";
        btn.classList.remove('bg-green-500');
    }, 1500);
}

// chọn variant
function setVariant(form) {
    let selected = document.querySelector('.variant-radio:checked');
    if (!selected) {
        alert('Vui lòng chọn phiên bản');
        return false;
    }
    form.variant_id.value = selected.value;
    return true;
}
</script>