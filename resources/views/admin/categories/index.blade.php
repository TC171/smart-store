@extends('admin.layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-white">Quản lý danh mục</h1>

    <div class="flex gap-3">
        <a href="{{ route('categories.create') }}"
           class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg">
            + Thêm danh mục
        </a>
    </div>
</div>

<div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">

    <div id="tableContainer">
        @include('admin.categories.partials.table')
    </div>

</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>

@endsection