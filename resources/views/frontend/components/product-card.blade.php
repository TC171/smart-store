<div class="bg-white rounded-lg p-2 shadow-sm hover:shadow-lg transition group">

    <!-- Ảnh -->
    <div class="aspect-square bg-gray-100 rounded overflow-hidden relative">

        <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : 'https://via.placeholder.com/300' }}"
             class="w-full h-full object-cover group-hover:scale-105 transition">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
            <button class="bg-white text-black px-3 py-1 rounded text-sm">
                Xem nhanh
            </button>
        </div>

    </div>

    <!-- Tên -->
    <h3 class="text-sm mt-2 line-clamp-2 min-h-10">
        {{ $product->name }}
    </h3>

    <!-- Giá -->
    <p class="text-red-500 font-bold mt-1">
        {{ number_format($product->variants->first()?->price ?? 0) }}đ
    </p>

</div>
