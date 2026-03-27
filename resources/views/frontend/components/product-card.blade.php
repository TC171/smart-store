<a href="{{ route('products.show', [
    $product->category->slug,
    $product->slug
]) }}"
   class="group/card block bg-white/80 backdrop-blur-sm rounded-3xl shadow-lg hover:shadow-2xl hover:shadow-orange-500/20 border border-white/30 hover:border-orange-200/50 overflow-hidden transition-all duration-500 hover:scale-[1.03] hover:shadow-xl active:scale-[0.98] w-full max-w-sm relative">

    <!-- Quick Actions Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 opacity-0 group-hover:opacity-100 transition-opacity z-20 flex flex-col justify-end p-4 backdrop-blur-sm">
        <div class="flex gap-2">
                            <button onclick="event.preventDefault(); quickAddToCart({{ $product->id }})" 
                    class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 active:from-orange-700 active:scale-95 text-white py-3 px-4 rounded-2xl font-bold text-sm shadow-xl hover:shadow-orange-500/50 transition-all duration-200 backdrop-blur-sm active:shadow-lg">
                <i class="fas fa-cart-plus mr-1"></i>Thêm Giỏ
            </button>
            <button onclick="event.preventDefault()"
                    class="w-12 h-12 bg-white/20 hover:bg-white backdrop-blur-sm rounded-2xl flex items-center justify-center text-white hover:text-gray-800 shadow-lg hover:shadow-xl transition-all">
                <i class="far fa-eye text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Image Container -->
    <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-gray-50 to-gray-100 pt-4 pb-6 px-4">
        <!-- Product Image -->
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

        <!-- Badges Stack -->
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

       

        <!-- Stock Ring Indicator -->
        <div class="absolute bottom-3 right-3 w-16 h-6 bg-emerald-500/90 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg z-20">
            {{ collect($product->variants)->sum('stock') ?? 0 }}+ Còn
        </div>
    </div>

    <!-- Content -->
    <div class="p-6 space-y-3">
        <!-- Title -->
        <h3 class="text-lg font-bold text-gray-800 line-clamp-2 leading-tight group-hover/card:text-orange-600 transition-colors h-12">
            {{ $product->name }}
        </h3>

        <!-- Price -->
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

        <!-- Ratings -->
        <div class="flex items-center gap-2">
            <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            
            <span class="text-xs text-gray-400 ml-auto">•</span>
            <span class="text-xs text-gray-500">
                {{ $product->sold_count ?? 0 }}+ bán
            </span>
        </div>

        <!-- Brand/Category Tags -->
        <div class="flex flex-wrap gap-1">
            @if($product->brand)
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-lg font-medium">
                    {{ $product->brand->name }}
                </span>
            @endif
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg font-medium">
                {{ optional($product->category)->name }}
            </span>
        </div>
    </div>

</a>

<script>
function toggleWishlist(id) {
    // Toggle heart animation
    event.currentTarget.querySelector('i').classList.toggle('far');
    event.currentTarget.querySelector('i').classList.toggle('fas');
    event.currentTarget.querySelector('i').style.color = 'rgb(239 68 68)';
}

function quickAddToCart(id) {
    // Show toast or cart flyout
    const btn = event.target.closest('button');
    btn.innerHTML = '<i class="fas fa-check mr-1"></i>Đã Thêm!';
    btn.classList.add('!bg-green-500');
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-cart-plus mr-1"></i>Thêm Giỏ';
        btn.classList.remove('!bg-green-500');
    }, 2000);
}
</script>

