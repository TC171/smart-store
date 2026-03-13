@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-white">Quản lý sản phẩm</h1>

    <div class="flex gap-3">
        <a href="{{ route('products.create') }}"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg">
            + Thêm sản phẩm
        </a>

        <form action="{{ route('products.import') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="flex items-center gap-2">
            @csrf
            <input type="file" 
                   name="file"
                   class="bg-gray-800 text-white border border-gray-700 rounded-lg px-2 py-1">

            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-lg">
                Import
            </button>
        </form>
    </div>
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

function toggleStatus(productId, button) {
    fetch("{{ url('/admin/products') }}/" + productId + "/toggle-status", {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Reload the table to update status display
            loadProducts();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>