@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-10">

    {{-- PRODUCT --}}
   <div class="max-w-7xl mx-auto p-6 space-y-10">

    {{-- PRODUCT --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

        {{-- IMAGE - chiếm nhiều không gian hơn --}}
        <div class="md:col-span-7 bg-white rounded-3xl shadow-lg p-6 flex flex-col items-center h-full">

            {{-- MAIN IMAGE --}}
            
         <div class="w-full aspect-square flex items-center justify-center bg-gray-50 rounded-xl overflow-hidden">
    <img id="mainImage"
         src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : 'https://via.placeholder.com/500' }}"
         class="w-full h-full object-contain transition-all duration-300">
</div>

            {{-- GALLERY --}}
            
            <div class="flex gap-3 mt-4 overflow-x-auto snap-x snap-mandatory py-2 w-full justify-start">
                {{-- THUMBNAIL --}}
@if($product->thumbnail)
    <img src="{{ asset('storage/'.$product->thumbnail) }}"
         class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-black snap-start transition-all"
         onclick="changeMainImage('{{ asset('storage/'.$product->thumbnail) }}')">
@endif
                @foreach($product->images as $img)
                
                    <img src="{{ asset('storage/'.$img->image) }}"
                         class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-black snap-start transition-all"
                         onclick="changeMainImage('{{ asset('storage/'.$img->image) }}')">
                @endforeach
                

                @foreach($product->variants->unique('color') as $variant)
                    @if($variant->image)
                        <img src="{{ asset('storage/'.$variant->image) }}"
                             class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-blue-500 snap-start transition-all"
                             onclick="changeMainImage('{{ asset('storage/'.$variant->image) }}')">
                    @endif
                @endforeach
            </div>
        </div>

        {{-- INFO - chiếm ít không gian hơn --}}
        <div class="md:col-span-5 bg-white shadow-xl rounded-3xl p-6 border border-gray-200 space-y-6 h-full flex flex-col justify-between">

            <div class="space-y-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                <div class="text-gray-500 text-sm">
                    {{ $product->category->name ?? '-' }} • {{ $product->brand->name ?? '-' }}
                </div>

                {{-- PRICE --}}
                <div class="bg-gray-50 p-4 rounded-xl text-center md:text-left">
                    <span id="price" class="text-3xl text-red-500 font-bold">
                        {{ number_format($minPrice) }} ₫
                    </span>
                </div>

                {{-- VARIANTS --}}
                <div class="space-y-4">
                    {{-- COLOR --}}
                    <div>
                        <h3 class="font-semibold mb-2 text-gray-700">Màu sắc</h3>
                        <div class="flex gap-2 flex-wrap">
                            @foreach($product->variants->pluck('color')->unique() as $color)
                                <button type="button"
                                    class="option-btn border px-3 py-2 rounded hover:bg-gray-100 transition"
                                    data-type="color"
                                    data-value="{{ $color }}">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- RAM/STORAGE --}}
                    <div class="bg-gray-50 p-4 rounded-xl space-y-2">
                        <h3 class="font-semibold mb-2 text-gray-700">Phiên bản</h3>
                        <div class="flex gap-2 flex-wrap" id="variant-options">
                            @foreach(
    $product->variants
        ->unique(function($v){
            return ($v->ram ?? '') . '-' . ($v->storage ?? '');
        }) as $variant
)
                                @php
                                    $label = $variant->ram ? "{$variant->ram} / {$variant->storage}" : $variant->storage;
                                @endphp
                                <button type="button"
                                    class="option-btn border px-3 py-2 rounded hover:bg-gray-100 transition"
                                    data-type="ram" 
                                    data-value="{{ $variant->ram ?? $variant->storage }}"
                                    data-storage="{{ $variant->storage }}"
                                    data-variant='@json($variant)'>
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <input type="hidden" id="variant_id">

                {{-- STOCK & QUANTITY --}}
                <div class="space-y-3">
                    <div class="text-sm text-gray-600">
                        Trong kho: <span id="stock" class="font-semibold text-green-600">--</span>
                    </div>
                    <div class="flex items-center border rounded-xl overflow-hidden w-fit">
                        <button onclick="changeQty(-1)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition">-</button>

                        <input type="number" id="quantity"
                            class="w-14 text-center outline-none"
                            value="1" min="1">

                        <button onclick="changeQty(1)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition">+</button>
                    </div>
                </div>
            </div>

            {{-- ACTION + SHORT DESC --}}
            <div class="space-y-4">
                <div class="flex gap-4 flex-col md:flex-row">
                    <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this, event)" class="flex-1">
                        @csrf
                        <input type="hidden" name="variant_id">
                        <button class="bg-black text-white px-6 py-3 rounded-xl hover:opacity-80 w-full transition">
                            🛒 Thêm vào giỏ
                        </button>
                    </form>

                    <form action="{{ route('cart.add') }}" method="POST" onsubmit="return setVariant(this, event)" class="flex-1">
                        @csrf
                        <input type="hidden" name="variant_id">
                        <input type="hidden" name="buy_now" value="1">
                        <button class="bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 w-full transition">
                            Mua ngay
                        </button>
                    </form>
                </div>

                <div>
                    <h3 class="font-semibold mb-1">Mô tả</h3>
                    <p class="text-gray-600 text-sm">{{ $product->short_description }}</p>
                </div>
            </div>

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
                        Sao chép
                    </button>

                </div>
            @endforeach
        </div>
    </div>

{{-- TAB DESCRIPTION + SPECS --}}
<div class="bg-white p-6 rounded-2xl shadow-lg">

    {{-- TAB BUTTON --}}
    <div class="flex gap-6 border-b mb-4">
        <button class="tab-btn active pb-2 border-b-2 border-black font-semibold"
                data-tab="description">
            Mô tả chi tiết
        </button>

        <button class="tab-btn pb-2 border-b-2 border-transparent text-gray-500"
                data-tab="specs">
            Thông số kỹ thuật
        </button>
    </div>

    {{-- CONTENT --}}
    <div>

        {{-- DESCRIPTION --}}
        <div id="tab-description" class="tab-content">

    <div class="relative">

        {{-- WRAPPER --}}
        <div id="descWrapper"
             class="overflow-hidden transition-all duration-500 ease-in-out relative">

            <div class="text-gray-700 prose max-w-none">
                {!! $product->description !!}
            </div>

            {{-- FADE --}}
            <div id="fadeOverlay"
                 class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-white to-transparent pointer-events-none">
            </div>
        </div>

        {{-- BUTTON --}}
        <button id="toggleDesc"
            class="mt-3 flex items-center gap-2 px-4 py-1 rounded-full border border-blue-500 text-blue-500 font-semibold hover:bg-blue-500 hover:text-white transition">

            <span>Xem thêm</span>

            <svg id="arrowIcon"
                 xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 transition-transform duration-300"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">

              <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>

        </button>

    </div>

</div>

        {{-- SPECS --}}
        {{-- SPECS --}}
<div id="tab-specs" class="tab-content hidden">
    <table class="w-full text-left border-collapse">
        <tbody>
            {{-- Thông tin cơ bản --}}
            <tr class="border-b">
                <th class="px-4 py-2 font-medium text-gray-700">Tên sản phẩm</th>
                <td class="px-4 py-2 text-gray-600">{{ $product->name }}</td>
            </tr>

            <tr class="border-b">
                <th class="px-4 py-2 font-medium text-gray-700">Danh mục</th>
                <td class="px-4 py-2 text-gray-600">{{ $product->category->name ?? '-' }}</td>
            </tr>

            <tr class="border-b">
                <th class="px-4 py-2 font-medium text-gray-700">Thương hiệu</th>
                <td class="px-4 py-2 text-gray-600">{{ $product->brand->name ?? '-' }}</td>
            </tr>

            {{-- Thông số kỹ thuật --}}
            @if($product->specs && $product->specs->count())
                @foreach($product->specs as $spec)
                <tr class="border-b">
                    <th class="px-4 py-2 font-medium text-gray-700">{{ $spec->name }}</th>
                    <td class="px-4 py-2 text-gray-600">{{ $spec->value }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

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

            {{-- ✅ BIẾN THỂ KHÁCH ĐÃ MUA --}}
            @if($review->variant)
                <div class="mt-2 flex items-center gap-3 bg-gray-50 p-2 rounded">

                    {{-- ảnh --}}
                    <img 
                        src="{{ asset('storage/'.($review->variant->image ?? $review->variant->product->image)) }}"
                        class="w-12 h-12 object-cover rounded border"
                    >

                    {{-- info --}}
                    <div class="text-xs text-gray-600">
                        <div class="font-medium text-gray-800">
                            {{ $review->variant->product->name }}
                        </div>

                        <div class="flex gap-2 flex-wrap mt-1">
                            @if($review->variant->color)
                                <span class="px-2 py-0.5 bg-gray-200 rounded">
                                    {{ $review->variant->color }}
                                </span>
                            @endif

                            @if($review->variant->ram || $review->variant->storage)
                                <span class="px-2 py-0.5 bg-gray-200 rounded">
                                    {{ $review->variant->ram ? $review->variant->ram.' / ' : '' }}
                                    {{ $review->variant->storage }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($review->title)
                <div class="font-medium mt-1">
                    {{ $review->title }}
                </div>
            @endif

            <div class="text-gray-600 text-sm mt-1">
                {{ $review->comment }}
            </div>

            {{-- ✅ ẢNH ĐÁNH GIÁ TỪ KHÁCH HÀNG --}}
            @php
                $imageUrls = $review->getImageUrls();
            @endphp
            @if(!empty($imageUrls))
                <div class="mt-3 flex gap-2 flex-wrap">
                    @foreach($imageUrls as $url)
                        <a href="{{ $url }}" target="_blank" class="block">
                            <img src="{{ $url }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition shadow-sm"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Review+Img&background=eee&color=999';">
                        </a>
                    @endforeach
                </div>
            @endif

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

                        @php
    $prices = $item->variants->pluck('price');
@endphp

@if($prices->count())
    <div class="text-red-500 font-semibold text-lg">
        @if($prices->min() == $prices->max())
            {{ number_format($prices->min()) }}₫
        @else
            {{ number_format($prices->min()) }}
            <span class="mx-1">-</span>
            {{ number_format($prices->max()) }}₫
        @endif
    </div>
@else
    <span class="text-gray-400 italic">Liên hệ</span>
@endif

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
    let selected = { color: null, ram: null, storage: null };

    const qtyInput = document.getElementById('quantity');
    const stockSpan = document.getElementById('stock');
    const mainImage = document.getElementById('mainImage');

    function updateStockUI(stock) {
        stockSpan.innerText = stock;
        const buttons = document.querySelectorAll('.action-btn, .change-qty-btn');
        if(stock <= 0){
            qtyInput.value = 0;
            qtyInput.disabled = true;
            buttons.forEach(b => b.disabled = true);
        } else {
            qtyInput.disabled = false;
            if(qtyInput.value < 1) qtyInput.value = 1;
            buttons.forEach(b => b.disabled = false);
        }
    }

    function findVariant() {
        // tìm variant hợp lệ theo những trường đã chọn
        let variant = variants.find(v => 
            (!v.color || selected.color === null || String(v.color).trim() === String(selected.color).trim()) &&
            (!v.ram || selected.ram === null || String(v.ram).trim() === String(selected.ram).trim()) &&
            (!v.storage || selected.storage === null || String(v.storage).trim() === String(selected.storage).trim())
        );

        if (variant) {
            document.getElementById('variant_id').value = variant.id;

            let price = variant.sale_price ? variant.sale_price : variant.price;
            document.getElementById('price').innerText =
                new Intl.NumberFormat('vi-VN').format(price) + ' ₫';

            updateStockUI(variant.stock);

            if(variant.image && selected.color){
    mainImage.src = '/storage/' + variant.image;
}
        } else {
            document.getElementById('variant_id').value = '';
            document.getElementById('price').innerText = '{{ number_format($minPrice) }} ₫';
            updateStockUI(0);
        }
    }

    function updateOptions() {
    // Nếu đã chọn color, chỉ show những variant hợp lệ
    const colorSelected = selected.color;
    const ramSelected = selected.ram;

    document.querySelectorAll(`[data-type="ram"]`).forEach(btn => {
        let ram = btn.dataset.value;
        let valid = variants.some(v => 
            (!colorSelected || String(v.color).trim() === String(colorSelected).trim()) &&
            String(v.ram ?? v.storage).trim() === String(ram).trim()
        );
        btn.classList.toggle('opacity-50', !valid);
        btn.classList.toggle('cursor-not-allowed', !valid);
        if(btn.classList.contains('border-black') && !valid){
            btn.classList.remove('border-black','bg-gray-200');
            selected.ram = null;
            selected.storage = null;
        }
    });

    document.querySelectorAll(`[data-type="storage"]`).forEach(btn => {
        let storage = btn.dataset.storage;
        let valid = variants.some(v => 
            (!colorSelected || String(v.color).trim() === String(colorSelected).trim()) &&
            (!ramSelected || String(v.ram).trim() === String(ramSelected).trim()) &&
            String(v.storage).trim() === String(storage).trim()
        );
        btn.classList.toggle('opacity-50', !valid);
        btn.classList.toggle('cursor-not-allowed', !valid);
        if(btn.classList.contains('border-black') && !valid){
            btn.classList.remove('border-black','bg-gray-200');
            selected.storage = null;
        }
    });
}

    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if(this.classList.contains('opacity-50')) return;

            let type = this.dataset.type;
            let value = this.dataset.value;

            // toggle selection
            if(this.classList.contains('border-black')){
                this.classList.remove('border-black','bg-gray-200');
                selected[type] = null;
                if(type === 'ram') selected['storage'] = null;
                findVariant();
                updateOptions();
                return;
            }

            // reset group
            document.querySelectorAll(`[data-type="${type}"]`).forEach(b => b.classList.remove('border-black','bg-gray-200'));

            this.classList.add('border-black','bg-gray-200');
            selected[type] = value;

            if(type === 'ram') selected['storage'] = this.dataset.storage;

            // nếu chọn color → cập nhật ảnh chính theo biến thể đầu tiên có màu đó
            if(type === 'color'){
                let variantWithColor = variants.find(v => String(v.color).trim() === String(value).trim() && v.image);
                if(variantWithColor){
                    mainImage.src = '/storage/' + variantWithColor.image;
                }
            }

            findVariant();
            updateOptions();
        });
    });

    window.changeQty = function(amount){
        let stock = parseInt(stockSpan.innerText) || 0;
        let current = parseInt(qtyInput.value) || 1;
        let next = current + amount;
        if(next < 1) next = 1;
        if(next > stock) next = stock;
        qtyInput.value = next;
    };

    window.setVariant = function(form, event){
    event.preventDefault();
    event.stopImmediatePropagation();

    let variantId = document.getElementById('variant_id').value;
    let quantityInput = document.getElementById('quantity');
    let quantity = parseInt(quantityInput.value);
    let stock = parseInt(document.getElementById('stock').innerText) || 0;

    if(!variantId){ alert('Vui lòng chọn đầy đủ phiên bản!'); return false; }

    // ✅ Lấy số lượng hiện có trong giỏ hàng (giả sử bạn có biến JS cartQuantity)
    let cartQuantity = parseInt(document.querySelector(`#cart-quantity-${variantId}`)?.innerText) || 0;

    let maxAvailable = stock - cartQuantity;

    if(quantity > maxAvailable){
        alert(`Chỉ còn ${maxAvailable} sản phẩm trong kho, sẽ thêm hết số này vào giỏ!`);
        quantity = maxAvailable;
        quantityInput.value = quantity;
    }

    if(quantity <= 0){
        alert('Sản phẩm đã hết hàng trong kho!');
        return false;
    }

    form.variant_id.value = variantId;
    let formData = new FormData(form);
    formData.append('quantity', quantity);

    fetch(form.action, {
        method:'POST',
        body: formData,
        headers:{
            'X-Requested-With':'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(!data.success){ 
            if(window.showToast) window.showToast(data.message || 'Có lỗi xảy ra', 'error');
            else alert(data.message); 
            return; 
        }
        if(data.redirect){ 
            window.location.href = data.redirect; 
            return;
        }

        // Cập nhật giao diện giỏ hàng
        if(window.updateMiniCartUI && data.cart) {
            const badge = document.querySelector('.cart-badge-count');
            if(badge) {
                badge.innerText = data.cart_count || Object.keys(data.cart).length;
                badge.classList.remove('animate-pulse');
                void badge.offsetWidth; 
                badge.classList.add('animate-pulse');
            }
            window.updateMiniCartUI(data.cart);
        }

        // Hiện thông báo đẹp
        if(window.showCartToast) {
            let itemName = data.message || 'Sản phẩm';
            let itemImage = null;
            let itemPrice = '';

            if (variantId && data.cart && data.cart[variantId]) {
                const item = data.cart[variantId];
                itemName = item.name || itemName;
                itemImage = item.image || null;
                itemPrice = new Intl.NumberFormat('vi-VN').format(item.price) + 'đ';
            }

            window.showCartToast(itemName, itemImage, itemPrice, data.cart_count || Object.keys(data.cart).length);
        } else if(window.showToast) {
            window.showToast('Đã thêm sản phẩm vào giỏ hàng', 'success');
        } else {
            alert(`Đã thêm ${quantity} sản phẩm vào giỏ!`);
        }
    })
    .catch(err => {
        console.error(err);
        if(window.showToast) window.showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
    });

    return false;
};

    // gọi lần đầu
    updateOptions();

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
<script>

// TAB SWITCH
document.addEventListener('DOMContentLoaded', function () {

    const descWrapper = document.getElementById('descWrapper');
    const fadeOverlay = document.getElementById('fadeOverlay');
    const toggleBtn = document.getElementById('toggleDesc');
    const arrowIcon = document.getElementById('arrowIcon');

    if (!descWrapper) return;

    let expanded = false;
    const collapsedHeight = 300; // 👈 chiều cao thu gọn (bạn chỉnh tuỳ thích)

    // set trạng thái ban đầu
    descWrapper.style.maxHeight = collapsedHeight + "px";

    toggleBtn.addEventListener('click', function () {

        if (!expanded) {
            descWrapper.style.maxHeight = descWrapper.scrollHeight + "px";
            fadeOverlay.style.display = 'none';
            toggleBtn.querySelector('span').innerText = "Thu gọn";
            arrowIcon.style.transform = "rotate(180deg)";
        } else {
            descWrapper.style.maxHeight = collapsedHeight + "px";
            fadeOverlay.style.display = 'block';
            toggleBtn.querySelector('span').innerText = "Xem thêm";
            arrowIcon.style.transform = "rotate(0deg)";
        }

        expanded = !expanded;
        
    });
    

});
// TAB SWITCH (QUAN TRỌNG)
document.addEventListener('DOMContentLoaded', function () {

    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {

            let target = this.dataset.tab;

            // reset tab
            tabs.forEach(t => {
                t.classList.remove('border-black','font-semibold');
                t.classList.add('text-gray-500','border-transparent');
            });

            // active tab
            this.classList.add('border-black','font-semibold');
            this.classList.remove('text-gray-500','border-transparent');

            // ẩn hết content
            contents.forEach(c => c.classList.add('hidden'));

            // hiện content đúng
            document.getElementById('tab-' + target).classList.remove('hidden');
        });
    });

});

</script>
<script>
function copyCoupon(code, btn) {
    if (!navigator.clipboard) {
        // fallback cho trình duyệt cũ
        let input = document.createElement("input");
        input.value = code;
        document.body.appendChild(input);
        input.select();
        document.execCommand("copy");
        document.body.removeChild(input);
    } else {
        navigator.clipboard.writeText(code);
    }

    // đổi text nút để báo đã copy
    let original = btn.innerText;
    btn.innerText = "Đã sao chép!";
    btn.classList.add("bg-green-500");

    setTimeout(() => {
        btn.innerText = original;
        btn.classList.remove("bg-green-500");
    }, 1500);
}
</script>
