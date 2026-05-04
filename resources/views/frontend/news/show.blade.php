@extends('frontend.layouts.app')

@section('title', $post->meta_title ?? $post->title . ' - Smart Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 bg-gray-50 min-h-screen">
    
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-gray-500 font-medium mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            </li>
            <li><div class="flex items-center"><svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><a href="{{ route('news.index') }}" class="hover:text-orange-500">Tin tức</a></div></li>
            @if($post->category)
            <li><div class="flex items-center"><svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><a href="{{ route('news.index', ['category' => $post->category->slug]) }}" class="hover:text-orange-500">{{ $post->category->name }}</a></div></li>
            @endif
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- NỘI DUNG CHÍNH --}}
        <div class="w-full lg:w-3/4">
            <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100">
                
                {{-- Category & Date --}}
                <div class="flex items-center gap-4 mb-4 text-xs font-semibold">
                    @if($post->category)
                    <a href="{{ route('news.index', ['category' => $post->category->slug]) }}" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full hover:bg-orange-200 transition-colors">
                        {{ $post->category->name }}
                    </a>
                    @endif
                    <span class="text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                        {{ $post->published_at ? $post->published_at->format('H:i d/m/Y') : $post->created_at->format('H:i d/m/Y') }}
                    </span>
                    <span class="text-gray-500 flex items-center gap-1 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        {{ number_format($post->views) }}
                    </span>
                </div>

                {{-- Tiêu đề --}}
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-6">
                    {{ $post->title }}
                </h1>

                @if($post->summary)
                <div class="text-lg text-gray-700 font-medium mb-8 p-5 bg-gray-50 rounded-xl border-l-4 border-orange-500 italic">
                    {{ $post->summary }}
                </div>
                @endif

                {{-- Chia sẻ mạng xã hội --}}
                <div class="flex items-center gap-3 mb-8 border-y border-gray-100 py-4">
                    <span class="font-semibold text-gray-600 text-sm">Chia sẻ bài viết:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>

                {{-- NỘI DUNG --}}
                <article class="prose prose-lg max-w-none prose-img:rounded-2xl prose-img:mx-auto prose-img:shadow-sm prose-headings:font-bold prose-a:text-orange-600 hover:prose-a:text-orange-700">
                    {!! $post->content !!}
                </article>

            </div>
        </div>

        {{-- SIDEBAR KHÁM PHÁ --}}
        <div class="w-full lg:w-1/4 space-y-6">
            {{-- Bài viết liên quan --}}
            @if($relatedPosts->count() > 0)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 sticky top-6">
                <h3 class="text-xl font-extrabold mb-5 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-orange-500 rounded-full inline-block"></span> 
                    Tin cùng chuyên mục
                </h3>
                <div class="space-y-4">
                    @foreach($relatedPosts as $rPost)
                    <a href="{{ route('news.show', $rPost->slug) }}" class="group flex gap-3 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                        <div class="w-24 h-16 rounded-xl overflow-hidden shrink-0 mt-1">
                            <img src="{{ $rPost->image ? asset('storage/'.$rPost->image) : asset('images/no-image.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 leading-snug group-hover:text-orange-500 transition-colors line-clamp-2">{{ $rPost->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1.5">{{ $rPost->published_at ? $rPost->published_at->diffForHumans() : $rPost->created_at->format('d/m/Y') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Banner khuyến mãi HOT SALE trong Bài viết --}}
            @if(isset($hotProducts) && $hotProducts->count() > 0)
            <div class="rounded-3xl overflow-hidden shadow-lg border border-orange-200 bg-gradient-to-br from-orange-400 to-red-500 text-white p-6 relative">
                 <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 blur-2xl rounded-full"></div>
                 <h4 class="font-black text-2xl mb-4 relative z-10 text-center">HOT SALE 🔥</h4>
                 
                 <div class="space-y-3 relative z-10">
                 @foreach($hotProducts as $product)
                     <a href="{{ route('product.detail', $product->slug) }}" class="flex bg-white/10 hover:bg-white/20 transition rounded-xl p-2 gap-3 items-center backdrop-blur-sm">
                         <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg bg-white p-1 shrink-0">
                         <div>
                             <h5 class="text-xs font-bold text-white line-clamp-2 leading-tight">{{ $product->name }}</h5>
                             <p class="text-yellow-300 font-black mt-1 text-sm">{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</p>
                         </div>
                     </a>
                 @endforeach
                 </div>
                 
                 <a href="{{ route('home') }}" class="mt-5 block text-center bg-white text-orange-600 font-bold px-4 py-2.5 rounded-xl text-sm shadow hover:bg-orange-50 transition relative z-10 w-full uppercase">Săn Sale Ngay</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
