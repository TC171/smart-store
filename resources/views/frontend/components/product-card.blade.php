@php
    $isWishlisted = auth('web')->check()
        ? auth('web')->user()->wishlists()->where('product_id', $product->id)->exists()
        : false;
@endphp

<div class="group/card block bg-white/80 backdrop-blur-sm rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/20 border border-white/30 hover:border-orange-200/50 overflow-hidden transition-all duration-500 hover:scale-[1.03] hover:shadow-xl active:scale-[0.98] w-full max-w-sm relative">

    {{-- ❤️ Wishlist Button --}}
    <button onclick="toggleWishlist(event, {{ $product->id }}, this)"
            data-wishlisted="{{ $isWishlisted ? '1' : '0' }}"
            data-login-url="{{ route('login') }}"
            data-logged-in="{{ auth('web')->check() ? '1' : '0' }}"
            class="absolute top-3 right-3 z-30 w-9 h-9 rounded-full bg-white/90 shadow-md flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95">
        <svg class="w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500' : 'text-gray-400' }}"
             fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </button>

    <a href="{{ route('products.show', [$product->category->slug, $product->slug]) }}" class="block">



        {{-- Image Container --}}
        <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-gray-50 to-gray-100 pt-4 pb-6 px-4">
            <div class="relative z-10">
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         class="w-full h-56 object-cover rounded-2xl mx-auto shadow-2xl transition-all duration-700 group-hover/card:scale-110 group-hover/card:rotate-2"
                         loading="lazy"
                         alt="{{ $product->name }}">
                @else
                    <div class="w-full h-56 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            {{-- Badges --}}
            <div class="absolute top-3 left-3 space-y-1 z-30">
                @if(isset($featured) && $featured)
                    <span class="bg-gradient-to-r from-orange-500 to-red-600 text-white text-xs px-3 py-1 rounded-2xl font-bold shadow-lg inline-flex items-center animate-bounce" style="animation-duration: 2s;">
                        🔥 HOT
                    </span>
                @endif
                @if(collect($product->variants)->where('stock', '>', 0)->count() === 0)
                    <span class="bg-gradient-to-r from-gray-500 to-gray-600 text-white text-xs px-3 py-1 rounded-2xl font-bold shadow-lg">
                        Hết Hàng
                    </span>
                @endif
            </div>

            {{-- Stock --}}
            <div class="absolute bottom-3 right-3 w-16 h-6 bg-emerald-500/90 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg z-20">
                {{ collect($product->variants)->sum('stock') ?? 0 }}+ Còn
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6 space-y-3">
            <h3 class="text-lg font-bold text-gray-800 line-clamp-2 leading-tight group-hover/card:text-orange-600 transition-colors h-12">
                {{ $product->name }}
            </h3>

            <div class="flex items-baseline gap-2">
                @php
                    $minPrice = $product->variants->min('price');
                    $maxPrice = $product->variants->max('price');
                @endphp
                <span class="text-2xl font-black text-red-600 drop-shadow-sm">
                    {{ number_format($minPrice ?? 0) }}đ
                </span>
                @if($minPrice !== null && $maxPrice !== null && $minPrice != $maxPrice)
                    <span class="text-xl font-bold text-gray-400 line-through">
                        {{ number_format($maxPrice) }}đ
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-xs text-gray-400 ml-auto">•</span>
                <span class="text-xs text-gray-500">{{ $product->sold_count ?? 0 }}+ bán</span>
            </div>

            <div class="flex flex-wrap gap-1">
                @if($product->brand)
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-lg font-medium">{{ $product->brand->name }}</span>
                @endif
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg font-medium">{{ optional($product->category)->name }}</span>
            </div>
        </div>

    </a>
</div>

<script>
if (typeof window.toggleWishlist === 'undefined') {
    window.toggleWishlist = function(event, productId, btn) {
        event.preventDefault();
        event.stopPropagation();

        // Đọc từ data attribute - không dùng Blade bên trong function
        const isLoggedIn = btn.dataset.loggedIn === '1';
        if (!isLoggedIn) {
            window.location.href = btn.dataset.loginUrl || '/login';
            return;
        }

        const svg = btn.querySelector('svg');
        const isWishlisted = btn.dataset.wishlisted === '1';

        // Optimistic UI
        if (isWishlisted) {
            svg.setAttribute('fill', 'none');
            svg.classList.remove('text-red-500');
            svg.classList.add('text-gray-400');
            btn.dataset.wishlisted = '0';
        } else {
            svg.setAttribute('fill', 'currentColor');
            svg.classList.add('text-red-500');
            svg.classList.remove('text-gray-400');
            btn.dataset.wishlisted = '1';
            btn.animate([{transform:'scale(1.4)'},{transform:'scale(1)'}], {duration:300, easing:'ease-out'});
        }

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.content : '';

        fetch('/wishlist/' + productId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(data => {
            btn.dataset.wishlisted = data.wishlisted ? '1' : '0';
            if (typeof showToast === 'function') {
                if (data.wishlisted) {
                    showToast('Đã thêm vào yêu thích ❤️', 'success');
                } else {
                    showToast('Đã xóa khỏi yêu thích', 'error');
                }
            }
        })
        .catch(err => {
            console.error('Wishlist error:', err);
            // revert
            if (isWishlisted) {
                svg.setAttribute('fill', 'currentColor');
                svg.classList.add('text-red-500');
                btn.dataset.wishlisted = '1';
            } else {
                svg.setAttribute('fill', 'none');
                svg.classList.remove('text-red-500');
                btn.dataset.wishlisted = '0';
            }
        });
    };
}
</script>
