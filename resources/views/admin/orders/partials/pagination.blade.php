@if($orders->hasPages())
    <div class="flex flex-wrap gap-2 items-center justify-center">
        @if($orders->onFirstPage())
            <span class="px-3 py-2 rounded bg-gray-800/60 text-gray-500">«</span>
        @else
            <a href="{{ $orders->previousPageUrl() }}"
               data-page="{{ $orders->currentPage() - 1 }}"
               class="px-3 py-2 rounded bg-gray-800/60 text-white hover:bg-white/10 transition">«</a>
        @endif

        @for($i = 1; $i <= $orders->lastPage(); $i++)
            @if($i == $orders->currentPage())
                <span class="px-3 py-2 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                    {{ $i }}
                </span>
            @else
                <a href="{{ $orders->url($i) }}"
                   data-page="{{ $i }}"
                   class="px-3 py-2 rounded bg-gray-800/60 text-white hover:bg-white/10 transition">
                    {{ $i }}
                </a>
            @endif
        @endfor

        @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}"
               data-page="{{ $orders->currentPage() + 1 }}"
               class="px-3 py-2 rounded bg-gray-800/60 text-white hover:bg-white/10 transition">»</a>
        @else
            <span class="px-3 py-2 rounded bg-gray-800/60 text-gray-500">»</span>
        @endif
    </div>
@endif