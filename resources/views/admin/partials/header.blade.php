<header class="flex justify-between items-center px-10 py-6 border-b border-purple-500/30">

    <h2 class="text-xl font-semibold tracking-wide">
        Admin Panel
    </h2>

    <div class="flex items-center gap-4">

        {{-- 🔔 PROFESSIONAL NOTIFICATION BELL --}}
        @php
            $pendingRefundsCount = \App\Models\RefundRequest::where('status', 'pending')->count();
            $latestRefunds = \App\Models\RefundRequest::with('user', 'order')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        @endphp

        <div class="relative">
            <button id="notiButton"
               class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 border border-purple-500/30 hover:bg-purple-600/20 transition cursor-pointer"
               title="Danh sách yêu cầu chờ duyệt">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($pendingRefundsCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-0.5 leading-none animate-pulse">
                        {{ $pendingRefundsCount > 99 ? '99+' : $pendingRefundsCount }}
                    </span>
                @endif
            </button>

            <!-- NOTIFICATION DROPDOWN -->
            <div id="notiDropdown"
                 class="hidden absolute right-0 mt-3 w-80
                        bg-black/95 backdrop-blur-2xl
                        border border-purple-500/30
                        rounded-2xl shadow-[0_0_40px_rgba(147,51,234,0.2)] z-50 overflow-hidden">
                
                <div class="px-5 py-4 border-b border-purple-500/20 bg-purple-600/10 flex justify-between items-center">
                    <span class="font-bold text-sm tracking-wide uppercase text-purple-300">Thông báo chờ xử lý</span>
                    <span class="text-[10px] bg-red-500 px-2 py-0.5 rounded-full font-bold">{{ $pendingRefundsCount }} yêu cầu</span>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse($latestRefunds as $refund)
                        <a href="{{ route('admin.refunds.show', $refund) }}"
                           class="block px-5 py-4 border-b border-white/5 hover:bg-purple-600/20 transition-all duration-300 group">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-400 shrink-0 border border-orange-500/30">
                                    🔄
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-white group-hover:text-purple-300 transition line-clamp-1">
                                        Yêu cầu #{{ $refund->id }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1 line-clamp-1">
                                        Khách: <span class="text-gray-200">{{ $refund->user->name ?? 'N/A' }}</span>
                                    </p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-[9px] bg-white/10 px-2 py-0.5 rounded text-gray-400">Đơn: #{{ $refund->order->order_number ?? '' }}</span>
                                        <span class="text-[9px] text-purple-400 font-medium">{{ $refund->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div class="text-3xl mb-3">✨</div>
                            <p class="text-gray-400 text-sm">Không có thông báo mới!</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.refunds.index') }}"
                   class="block py-3 text-center text-xs font-bold text-purple-400 hover:text-white hover:bg-purple-600 transition-all border-t border-purple-500/20">
                    XEM TẤT CẢ YÊU CẦU →
                </a>
            </div>
        </div>

        <!-- USER BUTTON -->
        <div class="relative">
            <button id="userButton"
                class="flex items-center gap-3 bg-white/5
                       px-4 py-2 rounded-xl border border-purple-500/30
                       hover:bg-purple-600/20 transition cursor-pointer">

                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>

                <span>{{ auth()->user()->name }}</span>
            </button>

            <!-- DROPDOWN -->
            <div id="userDropdown"
                 class="hidden absolute right-0 mt-3 w-52
                        bg-black/95 backdrop-blur-2xl
                        border border-purple-500/30
                        rounded-xl shadow-xl z-50">

                <a href="{{ route('admin.profile') }}"
                   class="block px-5 py-3 hover:bg-purple-600/20 transition">
                    👤 Profile
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-5 py-3 hover:bg-purple-600/20 transition">
                        🏠 Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}"
                       class="block px-5 py-3 hover:bg-purple-600/20 transition">
                        🏠 Customer Dashboard
                    </a>
                @endif

                <a href="{{ route('admin.password') }}"
                   class="block px-5 py-3 hover:bg-purple-600/20 transition">
                    🔐 Change Password
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-5 py-3 hover:bg-red-600/20 transition">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

    </div>

</header>

<script>
// Toggle Notifications
const notiBtn = document.getElementById('notiButton');
const notiDrop = document.getElementById('notiDropdown');
const userBtn = document.getElementById('userButton');
const userDrop = document.getElementById('userDropdown');

notiBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    notiDrop.classList.toggle('hidden');
    userDrop.classList.add('hidden');
});

userBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    userDrop.classList.toggle('hidden');
    notiDrop.classList.add('hidden');
});

document.addEventListener('click', (e) => {
    if (!notiDrop.contains(e.target) && !notiBtn.contains(e.target)) {
        notiDrop.classList.add('hidden');
    }
    if (!userDrop.contains(e.target) && !userBtn.contains(e.target)) {
        userDrop.classList.add('hidden');
    }
});
</script>