@extends('frontend.layouts.app')

@section('title', 'Tin công nghệ - Smart Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 bg-gray-50 min-h-screen">
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-gray-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-700">Tin tức 24h</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Tiêu đề trang & Danh mục --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 lg:p-6 rounded-2xl shadow-sm border border-gray-100">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Tin tức <span class="text-orange-500">24h</span></h1>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('news.index') }}" class="px-4 py-2 text-sm font-semibold rounded-full {{ !request()->has('category') ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                Tất cả tin
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('news.index', ['category' => $cat->slug]) }}" class="px-4 py-2 text-sm font-semibold rounded-full {{ request('category') == $cat->slug ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    @if(!request()->has('category') && $featuredPosts->count() > 0)
    {{-- Khối Tin Nổi Bật (Layout Cellphones) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @php $firstFeatured = $featuredPosts->first(); @endphp
        @if($firstFeatured)
        <a href="{{ route('news.show', $firstFeatured->slug) }}" class="lg:col-span-2 group relative rounded-2xl overflow-hidden shadow-lg h-80 md:h-[400px]">
            <img src="{{ $firstFeatured->image ? asset('storage/'.$firstFeatured->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $firstFeatured->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <span class="inline-block bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">{{ $firstFeatured->category->name ?? 'Tin Nổi Bật' }}</span>
                <h2 class="text-xl md:text-3xl font-bold text-white mb-2 leading-tight group-hover:text-orange-300 transition-colors">{{ $firstFeatured->title }}</h2>
                <div class="flex items-center text-gray-300 text-sm gap-4">
                    <span><i class="far fa-clock mr-1"></i>{{ $firstFeatured->published_at ? $firstFeatured->published_at->diffForHumans() : $firstFeatured->created_at->format('d/m/Y') }}</span>
                    <span><i class="far fa-eye mr-1"></i>{{ number_format($firstFeatured->views) }} lượt xem</span>
                </div>
            </div>
        </a>
        @endif

        <div class="flex flex-col gap-6">
            @foreach($featuredPosts->skip(1) as $fPost)
            <a href="{{ route('news.show', $fPost->slug) }}" class="group relative rounded-2xl overflow-hidden shadow h-40 md:h-[188px]">
                <img src="{{ $fPost->image ? asset('storage/'.$fPost->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $fPost->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-5">
                    <h3 class="text-lg font-bold text-white leading-tight group-hover:text-orange-300 transition-colors line-clamp-2">{{ $fPost->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Danh sách bài viết (Tin Mới Nhất) --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-2 h-6 bg-orange-500 rounded-full inline-block"></span> 
            {{ request()->has('category') ? 'Tin bài thuộc danh mục' : 'Tin tức mới nhất' }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($posts as $post)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-50 flex flex-col h-full">
                <a href="{{ route('news.show', $post->slug) }}" class="block relative aspect-[16/9] overflow-hidden">
                    <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $post->title }}">
                    @if($post->category)
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-orange-600 shadow-sm">
                        {{ $post->category->name }}
                    </span>
                    @endif
                </a>
                <div class="p-4 flex flex-col flex-grow">
                    <a href="{{ route('news.show', $post->slug) }}">
                        <h3 class="text-gray-900 font-bold text-[17px] leading-snug mb-2 group-hover:text-orange-500 transition-colors line-clamp-2">{{ $post->title }}</h3>
                    </a>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->summary ?? str_limit(strip_tags($post->content), 100) }}</p>
                    
                    <div class="mt-auto flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-3">
                        <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                        <span class="font-medium text-orange-500 group-hover:underline">Đọc tiếp &rarr;</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($posts->isEmpty())
        <div class="text-center py-10 bg-gray-50 rounded-2xl mt-6 border border-dashed border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1l2.293-2.293A1 1 0 0121 5.414v13.172a1 1 0 01-1.707.707L17 17v1a2 2 0 01-2 2z"></path></svg>
            <p class="text-gray-500 text-lg">Chưa có bài viết nào trong mục này.</p>
        </div>
        @endif

        <div class="mt-8 flex justify-center">
            {{ $posts->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
