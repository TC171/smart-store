@extends('frontend.layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">
        🔥 Sản phẩm nổi bật
    </h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

        @foreach($products as $product)
            @include('frontend.components.product-card', ['product' => $product])
        @endforeach

    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

</div>

@endsection