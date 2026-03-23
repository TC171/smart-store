@extends('frontend.layouts.app')

@section('content')

<div class="container py-4">

    <!-- Tiêu đề danh mục -->

    <h2 class="mb-4">
        Danh mục: {{ $category->name }}
    </h2>

    <div class="row">

        @forelse($products as $product)

        <div class="col-md-3 col-sm-6 mb-4">

            <div class="card h-100 shadow-sm">

                <!-- Ảnh sản phẩm -->

                <img
                    src="{{ asset('storage/' . $product->thumbnail) }}"
                    class="card-img-top"
                    style="height:200px; object-fit:cover;"
                    alt="{{ $product->name }}"
                >

                <div class="card-body text-center">

                    <!-- Tên sản phẩm -->

                    <h6 class="card-title">

                        {{ $product->name }}

                    </h6>

                    <!-- Giá -->

                    @if($product->variants->first())

                        <p class="text-danger fw-bold">

                            {{ number_format(
                                $product->variants->first()->price
                            ) }}đ

                        </p>

                    @endif

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">

            <p class="text-center">
                Không có sản phẩm trong danh mục này
            </p>

        </div>

        @endforelse

    </div>

    <!-- Phân trang -->

    <div class="mt-4">

        {{ $products->links() }}

    </div>

</div>

@endsection