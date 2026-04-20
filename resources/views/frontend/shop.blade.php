@extends('frontend.layouts.app')

@section('title', 'Cửa hàng')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-10">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Cửa hàng
                </h1>
                <p class="text-gray-500 mt-2">
                    Khám phá tất cả sản phẩm của chúng tôi.
                </p>
            </div>

            {{-- Products Grid --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-2xl shadow-lg p-4">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @endif
                            <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                            <p class="text-gray-600">Product ID: {{ $product->id }}</p>
                            <p class="text-orange-500 font-bold">{{ number_format($product->price) }}đ</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-500">Vui lòng thử lại với bộ lọc khác.</p>
                </div>
            @endif
        </div>
    </div>
@endsection