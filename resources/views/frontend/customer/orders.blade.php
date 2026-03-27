@extends('frontend.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 uppercase italic">Đơn hàng <span class="text-orange-500">của tôi</span></h1>
                <p class="text-gray-500 text-sm">Chào Nghĩa, bạn có thể quản lý và theo dõi đơn hàng tại đây</p>
            </div>
            <a href="/" class="text-orange-600 font-bold hover:underline">← Tiếp tục mua sắm</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white uppercase text-xs tracking-widest">
                        <th class="px-6 py-4">Mã đơn hàng</th>
                        <th class="px-6 py-4">Ngày đặt</th>
                        <th class="px-6 py-4">Tổng tiền</th>
                        <th class="px-6 py-4">Thanh toán</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-orange-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800">#{{ $order->order_number }}</span>
                            <div class="text-[10px] text-gray-400 uppercase font-bold">{{ $order->items->count() }} sản phẩm</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-black text-orange-600 italic text-lg">
                            {{ number_format($order->grand_total, 0, ',', '.') }}đ
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                @if($order->payment_status == 'paid')
                                    <span class="text-green-600 text-sm font-bold">● Đã thanh toán</span>
                                @else
                                    <span class="text-gray-400 text-sm font-medium italic">○ Chưa thanh toán</span>
                                @endif
                                <span class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $order->payment_method == 'vnpay' ? 'Qua VNPAY' : 'Tiền mặt (COD)' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-600',
                                    'confirmed' => 'bg-blue-100 text-blue-600',
                                    'shipping' => 'bg-orange-100 text-orange-600',
                                    'completed' => 'bg-green-100 text-green-600',
                                    'cancelled' => 'bg-red-100 text-red-600'
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusColors[$order->status] ?? 'bg-gray-100' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="#" title="Xem chi tiết" class="text-gray-400 hover:text-orange-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" 
                                          onsubmit="return confirm('Nghĩa ơi, bạn chắc chắn muốn hủy đơn hàng #{{ $order->order_number }} chứ?')">
                                        @csrf
                                        <button type="submit" title="Hủy đơn hàng" class="text-orange-600 hover:text-red-700 transition-all transform hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-gray-200 cursor-not-allowed" title="Không thể hủy đơn hàng này">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <p class="text-gray-400 font-medium italic">Nghĩa chưa có đơn hàng nào cả.</p>
                                <a href="/" class="mt-4 bg-orange-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-orange-700 transition-all shadow-lg shadow-orange-200 uppercase text-xs tracking-widest">Mua sắm ngay</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection