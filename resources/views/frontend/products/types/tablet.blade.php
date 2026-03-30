@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-10">

    {{-- PRODUCT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

        <div class="bg-white rounded-2xl shadow p-6">

    <div class="bg-white rounded-3xl shadow-lg p-8 border">

    {{-- ẢNH CHÍNH --}}
    {{-- ẢNH CHÍNH --}}
<img id="mainImage"
     src="{{ asset('storage/'.$product->thumbnail) }}"
     class="w-full md:h-[500px] h-[400px] object-contain rounded-xl">

    {{-- GALLERY --}}
    <div class="flex gap-3 mt-4 overflow-x-auto">

        {{-- ✅ ẢNH CHI TIẾT --}}
        @foreach($product->images as $img)
            <img src="{{ asset('storage/'.$img->image) }}"
                 class="w-20 h-20 object-cover rounded cursor-pointer border hover:border-black"
                 onclick="changeMainImage('{{ asset('storage/'.$img->image) }}')">
        @endforeach

        {{-- ✅ ẢNH BIẾN THỂ --}}
        @foreach($product->variants as $variant)
            @if($variant->image)
                <img src="{{ asset('storage/'.$variant->image) }}"
                     class="w-20 h-20 object-cover rounded cursor-pointer border hover:border-blue-500"
                     onclick="changeMainImage('{{ asset('storage/'.$variant->image) }}')">
            @endif
        @endforeach

    </div>

</div>

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
                <span id="price" class="text-3xl text-red-500 font-bold">
                    {{ number_format($minPrice) }} ₫
                </span>
            </div>

            {{-- VARIANT --}}
            <div>
                <h3 class="font-semibold mb-2">Chọn phiên bản</h3>

                {{-- COLOR --}}
                <div>
                    <h3 class="font-semibold mb-2">Màu sắc</h3>
                    <div class="flex gap-2">
                        @foreach($product->variants->pluck('color')->unique() as $color)
                            <button type="button"
                                class="option-btn border px-3 py-2 rounded"
                                data-type="color"
                                data-value="{{ $color }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- RAM --}}
                <div>
                    <h3 class="font-semibold mb-2">RAM</h3>
                    <div class="flex gap-2">
                        @foreach($product->variants->pluck('ram')->unique() as $ram)
                            <button type="button"
                                class="option-btn border px-3 py-2 rounded"
                                data-type="ram"
                                data-value="{{ $ram }}">
                                {{ $ram }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- STORAGE --}}
                <div>
                    <h3 class="font-semibold mb-2">Dung lượng</h3>
                    <div class="flex gap-2">
                        @foreach($product->variants->pluck('storage')->unique() as $storage)
                            <button type="button"
                                class="option-btn border px-3 py-2 rounded"
                                data-type="storage"
                                data-value="{{ $storage }}">
                                {{ $storage }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- hidden variant --}}
            <input type="hidden" id="variant_id">

            {{-- STOCK + QUANTITY --}}
<div class="space-y-3">
    <div class="text-sm text-gray-600">
        Trong kho: <span id="stock" class="font-semibold text-green-600">--</span>
    </div>

    <div class="flex items-center border rounded-xl overflow-hidden w-fit">
    <button onclick="changeQty(-1)"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200">-</button>

    <input type="number" id="quantity"
        class="w-14 text-center outline-none"
        value="1" min="1">

    <button onclick="changeQty(1)"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200">+</button>
</div>
</div>

            
            {{-- ACTION --}}
            <div class="flex gap-4 mt-4">

                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this, event)">
                    @csrf
                    <input type="hidden" name="variant_id">

                    <button class="bg-black text-white px-6 py-3 rounded-xl hover:opacity-80 w-full transition">
                        🛒 Thêm vào giỏ
                    </button>
                </form>

                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this, event)">
                    @csrf
                    <input type="hidden" name="variant_id">
                    <input type="hidden" name="buy_now" value="1">

                    <button class="bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 w-full transition">
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
        <div class="text-gray-700 prose max-w-none">
    {!! $product->description !!}
</div>
    </div>
    {{-- REVIEWS --}}
<div class="bg-white p-6 rounded-2xl shadow space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">⭐ Đánh giá khách hàng</h2>

        <div class="text-yellow-500 font-semibold">
            {{ $avgRating }}/5 ({{ $totalReviews }} đánh giá)
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex flex-wrap gap-2">

    <button data-rating=""
        class="filter-btn px-3 py-1 border rounded-lg {{ !request('rating') ? 'bg-black text-white' : '' }}">
        Tất cả
    </button>

    @for($i = 5; $i >= 1; $i--)
        <button data-rating="{{ $i }}"
            class="filter-btn px-3 py-1 border rounded-lg {{ request('rating') == $i ? 'bg-black text-white' : '' }}">
            {{ $i }} ⭐ ({{ $ratingCounts[$i] ?? 0 }})
        </button>
    @endfor

</div>

    {{-- LIST --}}
    <div id="review-list">
    @forelse($reviews as $review)
        <div class="border-b pb-4">

            <div class="flex justify-between items-center">
                <div class="font-semibold">
                    {{ $review->user->name ?? 'Ẩn danh' }}
                </div>

                <div class="text-yellow-500">
                    @for($i = 1; $i <= 5; $i++)
                        {!! $i <= $review->rating ? '⭐' : '☆' !!}
                    @endfor
                </div>
            </div>

            <div class="text-xs text-gray-400">
                {{ $review->created_at->format('d/m/Y') }}
            </div>

            @if($review->title)
                <div class="font-medium mt-1">
                    {{ $review->title }}
                </div>
            @endif

            <div class="text-gray-600 text-sm mt-1">
                {{ $review->comment }}
            </div>

        </div>
    @empty
        <div class="text-gray-500 text-center py-6">
            Chưa có đánh giá nào
        </div>
    @endforelse

    {{-- PAGINATION --}}
    <div>
        {{ $reviews->appends(request()->query())->links() }}
    </div>
    </div>

</div>
@if($relatedProducts->count())
<div class="bg-white p-6 rounded-2xl shadow">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">🔥 Sản phẩm liên quan</h2>

        <div class="flex gap-2">
            <button class="swiper-prev px-3 py-1 border rounded">‹</button>
            <button class="swiper-next px-3 py-1 border rounded">›</button>
        </div>
    </div>

    {{-- SLIDER --}}
    <div class="swiper relatedSwiper">
        <div class="swiper-wrapper">

            @foreach($relatedProducts as $item)
                <div class="swiper-slide">

                    <a href="{{ route('products.show', [$item->category->slug, $item->slug]) }}"
                       class="block border rounded-xl p-3 hover:shadow-lg transition bg-white">

                        <img src="{{ asset('storage/'.$item->thumbnail) }}"
                             class="h-40 w-full object-contain">

                        <div class="mt-2 text-sm font-semibold line-clamp-2">
                            {{ $item->name }}
                        </div>

                        <div class="text-red-500 font-bold">
                            {{ number_format($item->min_price ?? 0) }} ₫
                        </div>

                        {{-- LABEL --}}
                        <div class="text-xs mt-1">
                            @if($item->category_id == $product->category_id)
                                <span class="text-blue-500">Cùng danh mục</span>
                            @else
                                <span class="text-green-500">Phụ kiện</span>
                            @endif
                        </div>

                    </a>

                </div>
            @endforeach

        </div>
    </div>

</div>
@endif

@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {

    const variants = @json($product->variants);

    let selected = {
        color: null,
        ram: null,
        storage: null
    };

    document.querySelectorAll('.option-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        if (this.classList.contains('opacity-50')) return;

        let type = this.dataset.type;
        let value = this.dataset.value;

        // 👉 nếu đang được chọn rồi → bỏ chọn
        if (this.classList.contains('border-black')) {
            this.classList.remove('border-black','bg-gray-200');
            selected[type] = null;
        } else {
            // reset nhóm đó
            document.querySelectorAll(`[data-type="${type}"]`)
                .forEach(b => b.classList.remove('border-black','bg-gray-200'));

            // set active
            this.classList.add('border-black','bg-gray-200');
            selected[type] = value;
        }

        findVariant();
        updateOptions();
    });
});

    function findVariant() {
    if (!selected.color || !selected.ram || !selected.storage) return;

    let variant = variants.find(v =>
        String(v.color).trim() === String(selected.color).trim() &&
        String(v.ram).trim() === String(selected.ram).trim() &&
        String(v.storage).trim() === String(selected.storage).trim()
    );

    if (variant) {
        document.getElementById('variant_id').value = variant.id;

        let price = variant.sale_price ? variant.sale_price : variant.price;

        document.getElementById('price').innerText =
            new Intl.NumberFormat('vi-VN').format(price) + ' ₫';

        // ✅ HIỂN THỊ TỒN KHO
        document.getElementById('stock').innerText = variant.stock;

        // reset quantity
        document.getElementById('quantity').value = 1;
        // ✅ THÊM ĐOẠN NÀY
    if (variant.image) {
        document.getElementById('mainImage').src =
            '/storage/' + variant.image;
    }
    }
    
    
}
window.changeQty = function(amount) {
    let qtyInput = document.getElementById('quantity');
    let stock = parseInt(document.getElementById('stock').innerText) || 0;

    let current = parseInt(qtyInput.value) || 1;
    let next = current + amount;

    if (next < 1) next = 1;
    if (next > stock) next = stock;

    qtyInput.value = next;
};

    // 🔥 LOGIC DISABLE OPTION
    function updateOptions() {

        ['color', 'ram', 'storage'].forEach(type => {

            document.querySelectorAll(`[data-type="${type}"]`).forEach(btn => {

                let value = btn.dataset.value;

                // giả lập chọn
                let temp = { ...selected, [type]: value };

                // lọc variant hợp lệ
                let valid = variants.some(v => {

                    return (!temp.color || String(v.color).trim() === String(temp.color).trim()) &&
                           (!temp.ram || String(v.ram).trim() === String(temp.ram).trim()) &&
                           (!temp.storage || String(v.storage).trim() === String(temp.storage).trim());
                });

                if (valid) {
                    btn.classList.remove('opacity-50','cursor-not-allowed');
                } else {
                    btn.classList.add('opacity-50','cursor-not-allowed');
                }

            });
        });
    }

    // gọi lần đầu
    updateOptions();

    window.setVariant = function(form, event) {
    event.preventDefault();

    let variantId = document.getElementById('variant_id').value;
    let quantity = parseInt(document.getElementById('quantity').value);
    let stock = parseInt(document.getElementById('stock').innerText);

    if (!variantId) {
        alert('Vui lòng chọn đầy đủ phiên bản!');
        return false;
    }

    if (quantity > stock) {
        alert('Số lượng vượt quá hàng còn trong kho!');
        return false;
    }

    // 👉 truyền quantity
    form.variant_id.value = variantId;

    let formData = new FormData(form);
    formData.append('quantity', quantity);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message);
            return;
        }

        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            alert('Đã thêm vào giỏ!');
        }
    });

    return false;
};

});
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}
// 🔥 FILTER REVIEW AJAX
// ✅ FIX AJAX FILTER KHÔNG MẤT EVENT
document.addEventListener('click', function (e) {

    if (e.target.classList.contains('filter-btn')) {

        e.preventDefault();

        let rating = e.target.dataset.rating;

        let url = window.location.pathname;

        if (rating) {
            url += '?rating=' + rating;
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {

            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');

            let newList = doc.querySelector('#review-list');

            if (!newList) {
                console.error('Không tìm thấy review-list');
                return;
            }

            document.querySelector('#review-list').innerHTML = newList.innerHTML;

            // active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-black','text-white');
            });

            e.target.classList.add('bg-black','text-white');

        })
        .catch(err => console.error(err));
    }

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const swiper = new Swiper(".relatedSwiper", {
        slidesPerView: 2,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            640: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 5 }
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        loop: true,
        on: {
            init: function() {
                this.updateAutoHeight(); // ✅ Tính lại chiều cao
            },
            resize: function() {
                this.updateAutoHeight(); // resize vẫn gọn
            }
        },
        autoHeight: true, // ✅ Swiper tự cao theo nội dung
    });

});
</script>