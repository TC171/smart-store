@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Lịch sử kho hàng</h1>
        <a href="{{ route('inventory-history.create') }}"
           class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg font-semibold">
            + Cập nhật kho
        </a>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl space-y-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Loại</label>
                <select name="type" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">Tất cả loại</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Nhập kho</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Xuất kho</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Điều chỉnh</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Bán hàng</option>
                    <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Trả hàng</option>
                    <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Mua hàng</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Sản phẩm</label>
                <select name="product_variant_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">Tất cả sản phẩm</option>
                    @foreach($productVariants as $variant)
                        <option value="{{ $variant->id }}" {{ request('product_variant_id') == $variant->id ? 'selected' : '' }}>
                            {{ $variant->product ? $variant->product->name : 'Sản phẩm không tồn tại' }} - {{ $variant->sku }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Từ ngày</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Đến ngày</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Lọc
                </button>
                <a href="{{ route('inventory-history.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>

    {{-- Bảng --}}
    @if ($inventoryHistories->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Loại</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Số lượng</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Kho trước</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Kho sau</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Người thực hiện</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Thời gian</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Ghi chú</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventoryHistories as $history)
                <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                    <td class="px-6 py-4 text-white font-medium">{{ $history->id }}</td>

                    <td class="px-6 py-4">
                        <div class="text-white font-medium">
                            {{ $history->productVariant && $history->productVariant->product ? $history->productVariant->product->name : 'N/A' }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            {{ $history->productVariant ? $history->productVariant->sku : 'N/A' }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @switch($history->type)
                            @case('in')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                                    Nhập kho
                                </span>
                                @break
                            @case('out')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400">
                                    Xuất kho
                                </span>
                                @break
                            @case('adjustment')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400">
                                    Điều chỉnh
                                </span>
                                @break
                            @case('sale')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/20 text-purple-400">
                                    Bán hàng
                                </span>
                                @break
                            @case('return')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-500/20 text-gray-400">
                                    Trả hàng
                                </span>
                                @break
                            @case('purchase')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-400">
                                    Mua hàng
                                </span>
                                @break
                        @endswitch
                    </td>

                    <td class="px-6 py-4 text-white">{{ $history->quantity }}</td>
                    <td class="px-6 py-4 text-white">{{ $history->previous_stock }}</td>
                    <td class="px-6 py-4 text-white">{{ $history->current_stock }}</td>

                    <td class="px-6 py-4 text-gray-300">
                        {{ $history->user ? $history->user->name : 'Hệ thống' }}
                    </td>

                    <td class="px-6 py-4 text-gray-300 text-sm">
                        {{ $history->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-6 py-4 text-gray-300">
                        {{ $history->notes ?: '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('inventory-history.show', $history) }}"
                               class="text-cyan-500 hover:text-cyan-400 text-sm font-medium">
                                Xem
                            </a>

                            @if($history->reference_type === 'manual')
                            <form action="{{ route('inventory-history.destroy', $history) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa bản ghi này?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-400 text-sm font-medium">
                                    Xóa
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $inventoryHistories->links('pagination::tailwind') }}
    </div>

    @else
    <div class="text-center py-12 bg-gray-900 rounded-xl">
        <p class="text-gray-400 text-lg">Chưa có lịch sử kho hàng nào</p>
    </div>
    @endif

</div>

@endsection