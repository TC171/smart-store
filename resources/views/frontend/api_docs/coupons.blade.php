<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Store - API Coupons Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800">API <span class="text-red-600">COUPONS</span></h1>
                <p class="text-gray-500">Hệ thống quản lý mã giảm giá thời gian thực</p>
            </div>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">
                ● Server Status: Online
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-800 text-white text-sm uppercase">
                    <tr>
                        <th class="px-6 py-4">Mã Code</th>
                        <th class="px-6 py-4">Loại</th>
                        <th class="px-6 py-4">Giá trị</th>
                        <th class="px-6 py-4">Giảm tối đa</th>
                        <th class="px-6 py-4">Sử dụng</th>
                        <th class="px-6 py-4">Hết hạn</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($coupons as $cp)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-red-600">{{ $cp->code }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs font-bold uppercase">{{ $cp->type }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-700">
                            {{ $cp->type == 'percent' ? $cp->value.'%' : number_format($cp->value, 0, ',', '.').'đ' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $cp->max_discount ? number_format($cp->max_discount, 0, ',', '.').'đ' : 'Không giới hạn' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2 max-w-[100px]">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($cp->used_count / $cp->usage_limit) * 100 }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $cp->used_count }}/{{ $cp->usage_limit }} lượt</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 italic">
                            {{ \Carbon\Carbon::parse($cp->expires_at)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 p-4 bg-gray-800 rounded-xl text-xs font-mono text-gray-300">
            <span class="text-blue-400">GET</span> /api/coupons <span class="text-gray-500 ml-4">// Trả về dữ liệu JSON cho hệ thống Smart Store</span>
        </div>
    </div>
</body>
</html>