@if(isset($latestPosts) && $latestPosts->count() > 0)
<section data-aos="fade-up" class="pt-4">
    <div class="flex items-center justify-between mb-6 px-2">
        <div class="flex items-center gap-4">
            <h2 class="text-xl md:text-2xl font-black uppercase tracking-tight text-gray-900">Tin tức</h2>
            <span class="w-[1px] h-6 bg-gray-300"></span>
            <a href="{{ route('news.index') }}" class="text-sm font-semibold text-blue-600 hover:text-orange-500 transition-colors flex items-center gap-1 group">
                Xem tất cả 
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto pb-4 hide-scrollbar snap-x snap-mandatory">
        <div class="flex xl:grid xl:grid-cols-5 gap-4 min-w-max xl:min-w-0 px-2 lg:px-0">
            @foreach($latestPosts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group w-64 xl:w-auto flex-shrink-0 snap-start bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100/80 hover:shadow-xl hover:border-transparent hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                    <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/no-image.jpg') }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-4">
                    <h3 class="text-[15px] font-semibold text-gray-800 leading-snug line-clamp-2">{{ $post->title }}</h3>
                    <p class="text-xs text-gray-500 mt-2">{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
