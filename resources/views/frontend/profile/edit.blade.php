@extends('frontend.layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- Sidebar --}}
                <aside class="xl:col-span-4 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="h-28 bg-gradient-to-r from-orange-500 via-amber-500 to-yellow-400"></div>

                        <div class="px-6 pb-6 -mt-12">
                            <div
                                class="w-24 h-24 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center text-3xl font-bold text-orange-500 overflow-hidden">
                                @if (!empty($user->avatar))
                                    <img src="{{ asset($user->avatar) }}"
                                        alt="{{ $user->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full items-center justify-center text-3xl font-bold text-orange-500 hidden">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>

                            <div class="mt-4">
                                <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                                <p class="text-slate-500 text-sm mt-1">{{ $user->email }}</p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-semibold">
                                        Khách hàng
                                    </span>

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                        Thành viên từ {{ $user->created_at?->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                                    <p class="text-xs text-slate-500">Số điện thoại</p>
                                    <p class="font-bold text-slate-800 mt-1">{{ $user->phone ?: 'Chưa cập nhật' }}</p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                                    <p class="text-xs text-slate-500">Trạng thái</p>
                                    <p class="font-bold mt-1 {{ $user->status ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $user->status ? 'Đang hoạt động' : 'Tạm khóa' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-4">
                        <nav class="space-y-2">
                            <button type="button" onclick="showTab('profile-tab')"
                                class="w-full text-left px-4 py-3 rounded-2xl bg-orange-50 text-orange-600 font-semibold hover:bg-orange-100 transition">
                                Thông tin cá nhân
                            </button>

                            <button type="button" onclick="showTab('password-tab')"
                                class="w-full text-left px-4 py-3 rounded-2xl text-slate-700 font-semibold hover:bg-slate-50 transition">
                                Đổi mật khẩu
                            </button>

                            <a href="{{ route('customer.orders') }}"
                                class="block px-4 py-3 rounded-2xl text-slate-700 font-semibold hover:bg-slate-50 transition">
                                Đơn hàng của tôi
                            </a>

                            <a href="{{ route('home') }}"
                                class="block px-4 py-3 rounded-2xl text-slate-700 font-semibold hover:bg-slate-50 transition">
                                Về trang chủ
                            </a>
                        </nav>
                    </div>
                </aside>

                {{-- Main --}}
                <section class="xl:col-span-8 space-y-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 xl:p-5 transition-all hover:shadow-md">
                            <p class="text-xs md:text-sm text-slate-500 font-medium">Tổng đơn hàng</p>
                            <h3 class="text-2xl xl:text-3xl font-bold text-slate-900 mt-2">{{ $totalOrders ?? 0 }}</h3>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 xl:p-5 transition-all hover:shadow-md">
                            <p class="text-xs md:text-sm text-slate-500 font-medium">Đơn hoàn thành</p>
                            <h3 class="text-2xl xl:text-3xl font-bold text-green-600 mt-2">{{ $completedOrders ?? 0 }}</h3>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 xl:p-5 transition-all hover:shadow-md min-w-0">
                            <p class="text-xs md:text-sm text-slate-500 font-medium">Tổng chi tiêu</p>
                            <h3 class="text-2xl xl:text-3xl font-bold text-orange-500 mt-2" title="{{ number_format($totalSpent ?? 0) }}đ">
                                @php
                                    $spent = $totalSpent ?? 0;
                                    if ($spent >= 1000000000) {
                                        echo number_format($spent / 1000000000, 1, '.', '') . 'B';
                                    } elseif ($spent >= 1000000) {
                                        echo number_format($spent / 1000000, 1, '.', '') . 'M';
                                    } else {
                                        echo number_format($spent) . 'đ';
                                    }
                                @endphp
                            </h3>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 xl:p-5 transition-all hover:shadow-md">
                            <p class="text-xs md:text-sm text-slate-500 font-medium">Đơn chờ xử lý</p>
                            <h3 class="text-2xl xl:text-3xl font-bold text-amber-500 mt-2">{{ $pendingOrders ?? 0 }}</h3>
                        </div>
                    </div>

                    <div id="profile-tab" class="tab-panel bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-8">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Thông tin cá nhân</h2>
                                <p class="text-slate-500 mt-1">Cập nhật thông tin để quản lý tài khoản tốt hơn.</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="mb-5 rounded-2xl bg-green-50 border border-green-100 text-green-700 px-4 py-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            @method('PUT')

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Họ và tên</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full rounded-2xl border-slate-300 bg-slate-50 text-slate-500 cursor-not-allowed">
                                <p class="text-xs text-slate-400 mt-1">Email đăng nhập không chỉnh sửa tại đây.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Số điện thoại</label>
                                <input type="text" name="phone" id="phone_profile" 
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="Ví dụ: 0987 654 321"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                <p id="phone_profile_error" class="text-[11px] text-red-500 mt-1 hidden font-medium">Số điện thoại không hợp lệ (phải có 10 chữ số và bắt đầu bằng số 0).</p>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Giới tính</label>
                                <select name="gender"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ngày sinh</label>
                                <input type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', $user->date_of_birth ? \Illuminate\Support\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Địa chỉ mặc định</label>
                                <input type="text" name="address"
                                    value="{{ old('address', $defaultAddress->address ?? '') }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Thành phố</label>
                                <input type="text" name="city"
                                    value="{{ old('city', $defaultAddress->city ?? '') }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Quận / Huyện</label>
                                <input type="text" name="district"
                                    value="{{ old('district', $defaultAddress->district ?? '') }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('district')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Phường / Xã</label>
                                <input type="text" name="ward"
                                    value="{{ old('ward', $defaultAddress->ward ?? '') }}"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('ward')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                           

                            <div></div>

                            <div class="md:col-span-2 pt-2">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>

                        <div class="mt-8 pt-8 border-t border-slate-200">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Ảnh đại diện</h3>

                            @if ($errors->has('avatar'))
                                <div class="mb-4 rounded-xl bg-red-50 border border-red-100 text-red-700 px-4 py-3">
                                    {{ $errors->first('avatar') }}
                                </div>
                            @endif

                            <form action="{{ route('customer.profile.avatar') }}" method="POST" enctype="multipart/form-data"
                                class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                @csrf

                                <div class="w-20 h-20 rounded-full overflow-hidden bg-slate-100 flex items-center justify-center">
                                    @if (!empty($user->avatar))
                                        <img src="{{ asset($user->avatar) }}"
                                            alt="{{ $user->name }}"
                                            class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="w-full h-full items-center justify-center text-xl font-bold text-slate-400 hidden">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="text-xl font-bold text-slate-400">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <input type="file" name="avatar" accept=".jpg,.jpeg,.png"
                                        class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-slate-700 hover:file:bg-slate-200">
                                    <p class="text-xs text-slate-400 mt-2">Dung lượng tối đa 10MB. Định dạng JPG, JPEG, PNG.</p>
                                </div>

                                <button type="submit"
                                    class="px-5 py-3 rounded-2xl border border-slate-300 hover:bg-slate-50 transition font-medium text-slate-700">
                                    Cập nhật ảnh
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="password-tab" class="tab-panel hidden bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-slate-900">Đổi mật khẩu</h2>
                            <p class="text-slate-500 mt-1">Tăng cường bảo mật cho tài khoản của bạn.</p>
                        </div>

                        @if (session('success_password'))
                            <div class="mb-5 rounded-2xl bg-green-50 border border-green-100 text-green-700 px-4 py-3">
                                {{ session('success_password') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu mới</label>
                                <input type="password" name="password"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded-2xl border-slate-300 focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-semibold hover:bg-black transition">
                                    Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-8">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Đơn hàng gần đây</h2>
                                <p class="text-slate-500 mt-1">Theo dõi nhanh các đơn hàng mới nhất của bạn.</p>
                            </div>

                            <a href="{{ route('customer.orders') }}" class="text-orange-500 font-semibold hover:underline">
                                Xem tất cả
                            </a>
                        </div>

                        @if (!empty($recentOrders) && count($recentOrders))
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-500 border-b border-slate-100">
                                            <th class="pb-3">Mã đơn</th>
                                            <th class="pb-3">Ngày đặt</th>
                                            <th class="pb-3">Tổng tiền</th>
                                            <th class="pb-3">Thanh toán</th>
                                            <th class="pb-3">Trạng thái</th>
                                            <th class="pb-3 text-right">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50 transition-colors">
                                                <td class="py-4 font-semibold text-slate-800 tracking-tight">{{ $order->order_number }}</td>
                                                <td class="py-4 text-slate-500">{{ $order->created_at?->format('d/m/Y') }}</td>
                                                <td class="py-4 font-black text-slate-900">{{ number_format($order->grand_total) }}đ</td>
                                                <td class="py-4">
                                                    @if($order->payment_status === 'paid')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-green-50 text-green-700 text-[10px] font-black uppercase tracking-wider border border-green-100">
                                                            <span class="w-1 h-1 rounded-full bg-green-500"></span>
                                                            Đã thanh toán
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider border border-slate-100">
                                                            <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                                                            Chưa thanh toán
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4">
                                                    @php
                                                        $statusLabels = [
                                                            'pending' => 'Chờ xác nhận',
                                                            'confirmed' => 'Đã xác nhận',
                                                            'shipping' => 'Đang giao hàng',
                                                            'completed' => 'Hoàn thành',
                                                            'cancelled' => 'Đã huỷ',
                                                            'refunded' => 'Đã hoàn hàng',
                                                            'failed_delivery' => 'Giao không thành công'
                                                        ];
                                                        $statusColors = [
                                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                            'confirmed' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                            'shipping' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                            'completed' => 'bg-green-50 text-green-600 border-green-100',
                                                            'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                                            'refunded' => 'bg-slate-50 text-slate-600 border-slate-100',
                                                            'failed_delivery' => 'bg-rose-50 text-rose-600 border-rose-100'
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-100' }}">
                                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 text-right">
                                                    <a href="{{ route('customer.order.detail', $order->id) }}"
                                                        class="inline-flex items-center gap-1 text-orange-500 font-bold hover:text-orange-600 transition tracking-tight">
                                                        Xem chi tiết
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-500">
                                Bạn chưa có đơn hàng nào.
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        function showTab(id) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
            document.getElementById(id).classList.remove('hidden');
        }

        // Logic Validate và Định dạng Số điện thoại cho Profile
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone_profile');
            const phoneError = document.getElementById('phone_profile_error');

            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,3})(\d{0,3})/);
                    if (x[1]) {
                        e.target.value = x[1] + (x[2] ? ' ' + x[2] : '') + (x[3] ? ' ' + x[3] : '');
                    }

                    const rawValue = e.target.value.replace(/\s/g, '');
                    const vnPhoneRegex = /^(0[3|5|7|8|9])[0-9]{8}$/;
                    
                    if (rawValue.length > 0 && !vnPhoneRegex.test(rawValue)) {
                        phoneInput.classList.add('border-red-500', 'bg-red-50');
                        phoneError.classList.remove('hidden');
                    } else {
                        phoneInput.classList.remove('border-red-500', 'bg-red-50');
                        phoneError.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endsection