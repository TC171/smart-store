@extends('frontend.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-green-500 p-8 text-center relative">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h1 class="text-2xl font-black text-white uppercase tracking-wide">Thanh toán thành công!</h1>
                    <p class="text-green-100 text-sm mt-1 opacity-90">Cảm ơn bạn đã tin tưởng Smart Store</p>
                </div>
            </div>

            <div class="p-8">
                <div class="space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b border-dashed border-gray-200">
                        <span class="text-gray-500 text-sm">Mã đơn hàng:</span>
                        <span class="font-bold text-gray-800">#{{ $order->order_number }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Số tiền đã thanh toán:</span>
                        <span class="text-xl font-black text-red-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 text-sm border border-gray-100">
                        <div class="flex justify-between">
                            <span class="text-gray-400 font-medium">Phương thức:</span>
                            <span class="font-bold text-gray-700">VNPAY (Online)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400 font-medium">Thời gian giao dịch:</span>
                            <span class="font-bold text-gray-700">{{ now()->format('H:i:s d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400 font-medium">Mã giao dịch VNPAY:</span>
                            <span class="font-bold text-blue-600">{{ request()->vnp_TransactionNo ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400 font-medium">Trạng thái đơn hàng:</span>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-[10px] font-black uppercase italic">Đã xác nhận</span>
                        </div>
                    </div>

                    <div class="pt-4 space-y-3">
                        <a href="{{ route('customer.orders') }}" class="block w-full bg-gray-900 text-white text-center font-bold py-4 rounded-xl hover:bg-black transition-all shadow-lg text-sm uppercase tracking-widest">
                            Quản lý đơn hàng
                        </a>
                        <a href="/" class="block w-full bg-white text-gray-500 text-center font-bold py-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all text-sm uppercase tracking-widest">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Hệ thống bảo mật Smart Store
                </p>
            </div>
        </div>
    </div>
</div>
@endsection