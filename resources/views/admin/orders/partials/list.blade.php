<div id="tableContainerInner">
    @include('admin.orders.partials.table', ['orders' => $orders])
</div>

<div class="mt-4" id="paginationContainer">
    {{-- pagination ajax: gắn data-page --}}
    @if ($orders->hasPages())
        <div class="flex justify-end">
            {!! $orders->onEachSide(1)->links() !!}
        </div>
    @endif
</div>