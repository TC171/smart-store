@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-white">
    <h1 class="text-2xl font-bold mb-6">Create Product</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label>Name</label>
            <input type="text" name="name"
                   class="w-full bg-gray-800 p-2 rounded">
        </div>

        <div class="mb-4">
            <label>Price</label>
            <input type="number" name="price"
                   class="w-full bg-gray-800 p-2 rounded">
        </div>

        <button class="bg-cyan-500 px-4 py-2 rounded">
            Save
        </button>
    </form>
</div>
@endsection