@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold text-white mb-6">Danh sách Đánh giá sản phẩm</h1>

    @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl space-y-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <input type="text" name="search" placeholder="Tìm theo tên sản phẩm..." value="{{ request('search') }}"
                    class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>

            <div>
                <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chưa duyệt</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                </select>
            </div>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Lọc
            </button>
        </form>
    </div>

    {{-- Bảng --}}
    @if ($reviews->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Đánh giá</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Bình luận</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Ngày</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                    <td class="px-6 py-4 text-gray-300">
                        {{ $review->user->name ?? 'Khách' }}
                    </td>

                    <td class="px-6 py-4 text-gray-300">
                        {{ $review->product->name ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-300">
                        <span class="text-sm line-clamp-2">
                            {{ Str::limit($review->comment, 100) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @php
                        $status = $review->is_approved ? 'approved' : 'pending';
                        $statusLabels = [
                            'approved' => 'Đã duyệt',
                            'pending' => 'Chưa duyệt',
                        ];
                        $statusColors = [
                            'approved' => 'bg-green-500/20 text-green-400',
                            'pending' => 'bg-yellow-500/20 text-yellow-400',
                        ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $statusLabels[$status] ?? $status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-300 text-sm">
                        {{ $review->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            @if (!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-green-500 hover:text-green-400 text-sm font-medium">
                                    Duyệt
                                </button>
                            </form>

                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-orange-500 hover:text-orange-400 text-sm font-medium">
                                    Từ chối
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Xác nhận xoá đánh giá này?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-400 text-sm font-medium">
                                    Xoá
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $reviews->links('pagination::tailwind') }}
    </div>

    @else
    <div class="text-center py-12 bg-gray-900 rounded-xl">
        <p class="text-gray-400 text-lg">Chưa có đánh giá nào</p>
    </div>
    @endif

</div>

@endsection
