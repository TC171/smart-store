@extends('frontend.layouts.app')

@section('title', $category->name)

@section('content')

<div class="max-w-7xl mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">
        📦 {{ $category->name }}
    </h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @forelse($products as $product)
            @include('frontend.components.product-card', ['product' => $product])
        @empty
            <p class="col-span-full text-center py-12 text-gray-500">
                Chưa có sản phẩm trong danh mục này
            </p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->appends(request()->query())->links() }}
    </div>

</div>

@endsection

