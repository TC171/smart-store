<header class="flex justify-between items-center px-10 py-6 border-b border-purple-500/30">

    <h2 class="text-xl font-semibold tracking-wide">
        Admin Panel
    </h2>

    <div class="flex items-center gap-4">

        {{-- ================= AI MENU ================= --}}
        <div class="relative">

            <!-- AI BUTTON -->
            <button id="aiMenuBtn"
                class="flex items-center gap-2 
                       bg-white/5 border border-purple-500/30
                       px-4 py-2 rounded-xl text-white
                       hover:bg-purple-600/20 transition">

                <span class="text-lg">🤖</span>
                <span class="font-medium">AI</span>

                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- AI DROPDOWN -->
            <div id="aiDropdown"
                 class="hidden absolute right-0 mt-3 w-56
                        bg-black/95 backdrop-blur-xl
                        border border-purple-500/20
                        rounded-2xl shadow-2xl overflow-hidden z-50">

                <div class="px-5 py-3 text-xs text-gray-400 border-b border-purple-500/10">
                    AI MANAGEMENT
                </div>

                <a href="{{ route('admin.ai.settings') }}"
                   class="flex items-center gap-3 px-5 py-3 hover:bg-purple-600/20 transition">
                    ⚙️ Cài đặt AI
                </a>

                <a href="{{ route('admin.ai.users') }}"
                   class="flex items-center gap-3 px-5 py-3 hover:bg-purple-600/20 transition">
                    💬 Lịch sử chat
                </a>
            </div>
        </div>

        {{-- ================= NOTIFICATION ================= --}}
        @php
            $pendingRefundsCount = \App\Models\RefundRequest::where('status', 'pending')->count();
            $latestRefunds = \App\Models\RefundRequest::with('user','order')
                ->where('status', 'pending')
                ->orderBy('created_at','desc')
                ->take(5)
                ->get();
        @endphp

        <div class="relative">

            <button id="notiButton"
               class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 border border-purple-500/30 hover:bg-purple-600/20 transition">

                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>

                @if($pendingRefundsCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center">
                        {{ $pendingRefundsCount > 99 ? '99+' : $pendingRefundsCount }}
                    </span>
                @endif
            </button>

            <!-- NOTI DROPDOWN -->
            <div id="notiDropdown"
                 class="hidden absolute right-0 mt-3 w-80
                        bg-black/95 backdrop-blur-2xl
                        border border-purple-500/30
                        rounded-2xl shadow-[0_0_40px_rgba(147,51,234,0.2)] z-50 overflow-hidden">

                <div class="px-5 py-4 border-b border-purple-500/20 bg-purple-600/10 flex justify-between">
                    <span class="font-bold text-sm text-purple-300">Thông báo</span>
                    <span class="text-[10px] bg-red-500 px-2 py-0.5 rounded-full">
                        {{ $pendingRefundsCount }}
                    </span>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse($latestRefunds as $refund)
                        <a href="{{ route('admin.refunds.show', $refund) }}"
                           class="block px-5 py-4 border-b border-white/5 hover:bg-purple-600/20">
                            <p class="text-xs text-white">Yêu cầu #{{ $refund->id }}</p>
                            <p class="text-[10px] text-gray-400">
                                {{ $refund->user->name ?? 'N/A' }}
                            </p>
                        </a>
                    @empty
                        <div class="px-5 py-6 text-center text-gray-400 text-sm">
                            Không có thông báo
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.refunds.index') }}"
                   class="block text-center py-3 text-purple-400 hover:bg-purple-600/20 border-t border-purple-500/20">
                    Xem tất cả
                </a>
            </div>
        </div>

        {{-- ================= USER ================= --}}
        <div class="relative">

            <button id="userButton"
                class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-xl border border-purple-500/30 hover:bg-purple-600/20 transition">

                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>

                <span>{{ auth()->user()->name }}</span>
            </button>

            <div id="userDropdown"
                 class="hidden absolute right-0 mt-3 w-52
                        bg-black/90 backdrop-blur-xl
                        border border-purple-500/30
                        rounded-xl shadow-xl z-50">

                <a href="{{ route('admin.profile') }}" class="block px-5 py-3 hover:bg-purple-600/20">
                    👤 Profile
                </a>

                @if(in_array(auth()->user()->role, ['admin','staff']))
                    <a href="{{ route('admin.dashboard') }}" class="block px-5 py-3 hover:bg-purple-600/20">
                        🏠 Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="block px-5 py-3 hover:bg-purple-600/20">
                        🏠 Customer Dashboard
                    </a>
                @endif

                <a href="{{ route('admin.password') }}" class="block px-5 py-3 hover:bg-purple-600/20">
                    🔐 Change Password
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="w-full text-left px-5 py-3 hover:bg-red-600/20">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const aiBtn = document.getElementById("aiMenuBtn");
    const aiDrop = document.getElementById("aiDropdown");

    const notiBtn = document.getElementById('notiButton');
    const notiDrop = document.getElementById('notiDropdown');

    const userBtn = document.getElementById('userButton');
    const userDrop = document.getElementById('userDropdown');

    // AI toggle
    aiBtn?.addEventListener("click", function (e) {
        e.stopPropagation();
        aiDrop.classList.toggle("hidden");
        notiDrop?.classList.add("hidden");
        userDrop?.classList.add("hidden");
    });

    // NOTI toggle
    notiBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        notiDrop.classList.toggle('hidden');
        userDrop.classList.add('hidden');
        aiDrop.classList.add('hidden');
    });

    // USER toggle
    userBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        userDrop.classList.toggle('hidden');
        notiDrop.classList.add('hidden');
        aiDrop.classList.add('hidden');
    });

    // click outside
    document.addEventListener('click', () => {
        aiDrop?.classList.add('hidden');
        notiDrop?.classList.add('hidden');
        userDrop?.classList.add('hidden');
    });

});
</script>