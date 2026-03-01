@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Quản lý sản phẩm</h1>
        <a href="{{ route('products.create') }}"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg">
            + Thêm sản phẩm
        </a>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">

        <div id="tableContainer">
            @include('admin.products.partials.table')
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

</div>
@endsection

<script>
let urlParams = new URLSearchParams(window.location.search);

let filters = {
    category_id: urlParams.get('category_id') || '',
    brand_id: urlParams.get('brand_id') || '',
    sort_price: urlParams.get('sort_price') || '',
    status: urlParams.get('status') || '',
};

document.addEventListener('click', function(e){

    if(e.target.closest('#sortPrice')){
        if (filters.sort_price === 'asc') {
            filters.sort_price = 'desc';
            document.getElementById('priceIcon').innerText = '↓';
        } else {
            filters.sort_price = 'asc';
            document.getElementById('priceIcon').innerText = '↑';
        }
        loadProducts();
    }

});
document.getElementById('filterStatus').addEventListener('change', function(){
    filters.status = this.value;
    loadProducts();
});

function loadProducts() {
    let query = new URLSearchParams(filters).toString();

    fetch("{{ route('products.index') }}?" + query, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('tableContainer').innerHTML = data;
    });
}
</script>