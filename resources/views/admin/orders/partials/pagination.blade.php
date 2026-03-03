@if ($orders->hasPages())
    <div class="flex flex-wrap gap-2 items-center">
        {{-- Prev --}}
        @if ($orders->onFirstPage())
            <span class="px-3 py-2 rounded bg-white/5 text-gray-500">«</span>
        @else
            <a href="#" data-page="{{ $orders->currentPage() - 1 }}"
               class="px-3 py-2 rounded bg-white/5 hover:bg-white/10 text-white">«</a>
        @endif

        {{-- Pages --}}
        @for ($i = 1; $i <= $orders->lastPage(); $i++)
            @if ($i == $orders->currentPage())
                <span class="px-3 py-2 rounded bg-cyan-500/20 text-cyan-200 border border-cyan-400/30">
                    {{ $i }}
                </span>
            @else
                <a href="#" data-page="{{ $i }}"
                   class="px-3 py-2 rounded bg-white/5 hover:bg-white/10 text-white">
                    {{ $i }}
                </a>
            @endif
        @endfor

        {{-- Next --}}
        @if ($orders->hasMorePages())
            <a href="#" data-page="{{ $orders->currentPage() + 1 }}"
               class="px-3 py-2 rounded bg-white/5 hover:bg-white/10 text-white">»</a>
        @else
            <span class="px-3 py-2 rounded bg-white/5 text-gray-500">»</span>
        @endif
    </div>
@endif