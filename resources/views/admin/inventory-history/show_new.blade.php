@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Chi tiết lịch sử kho #{{ $inventoryHistory->id }}</h1>
        <a href="{{ route('inventory-history.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Quay lại
        </a>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg p-6">

        {{-- Thông tin cơ bản --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">ID</label>
                    <p class="text-white text-lg font-semibold">{{ $inventoryHistory->id }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sản phẩm</label>
                    <div class="text-white">
                        <div class="font-medium">
                            {{ $inventoryHistory->productVariant && $inventoryHistory->productVariant->product ? $inventoryHistory->productVariant->product->name : 'N/A' }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            SKU: {{ $inventoryHistory->productVariant ? $inventoryHistory->productVariant->sku : 'N/A' }}
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Loại</label>
                    @switch($inventoryHistory->type)
                        @case('in')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-500/20 text-green-400">
                                Nhập kho
                            </span>
                            @break
                        @case('out')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-500/20 text-yellow-400">
                                Xuất kho
                            </span>
                            @break
                        @case('adjustment')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-blue-500/20 text-blue-400">
                                Điều chỉnh
                            </span>
                            @break
                        @case('sale')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-purple-500/20 text-purple-400">
                                Bán hàng
                            </span>
                            @break
                        @case('return')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gray-500/20 text-gray-400">
                                Trả hàng
                            </span>
                            @break
                        @case('purchase')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-indigo-500/20 text-indigo-400">
                                Mua hàng
                            </span>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Số lượng</label>
                    <p class="text-white text-lg font-semibold">{{ $inventoryHistory->quantity }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Kho trước</label>
                    <p class="text-white text-lg">{{ $inventoryHistory->previous_stock }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Kho sau</label>
                    <p class="text-white text-lg">{{ $inventoryHistory->current_stock }}</p>
                </div>
            </div>

        </div>

        {{-- Thông tin bổ sung --}}
        <div class="border-t border-gray-700 pt-6 space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Người thực hiện</label>
                <p class="text-white">{{ $inventoryHistory->user ? $inventoryHistory->user->name : 'Hệ thống' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Thời gian</label>
                <p class="text-white">{{ $inventoryHistory->created_at->format('d/m/Y H:i:s') }}</p>
            </div>

            @if($inventoryHistory->notes)
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Ghi chú</label>
                <p class="text-white bg-gray-800 p-3 rounded-lg">{{ $inventoryHistory->notes }}</p>
            </div>
            @endif

            @if($inventoryHistory->reference_type)
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Loại tham chiếu</label>
                <p class="text-white">{{ $inventoryHistory->reference_type }}</p>
            </div>
            @endif

            @if($inventoryHistory->reference_id)
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">ID tham chiếu</label>
                <p class="text-white">{{ $inventoryHistory->reference_id }}</p>
            </div>
            @endif

        </div>

    </div>

</div>

@endsection