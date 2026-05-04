<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')->where('status', 1)->where('published_at', '<=', now());

        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $featuredPosts = Post::where('status', 1)->where('is_featured', 1)->where('published_at', '<=', now())->orderBy('published_at', 'desc')->take(3)->get();
        $posts = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = PostCategory::where('status', 1)->orderBy('sort_order')->get();

        return view('frontend.news.index', compact('posts', 'featuredPosts', 'categories'));
    }

    public function show($slug)
    {
        $post = Post::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();
        
        $post->increment('views');

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 1)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $hotProducts = \App\Models\Product::where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(2)->get();

        return view('frontend.news.show', compact('post', 'relatedPosts', 'hotProducts'));
    }
}
