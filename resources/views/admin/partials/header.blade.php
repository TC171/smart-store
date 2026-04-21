<header class="flex justify-between items-center 
               px-10 py-6 border-b border-purple-500/30">

    <h2 class="text-xl font-semibold tracking-wide">
        Admin Panel
    </h2>

    <div class="flex items-center gap-4">

       <div class="flex items-center gap-2">

    <div class="relative">

   <div class="relative">

    <!-- NÚT AI -->
    <button id="aiMenuBtn"
        class="flex items-center gap-2 
               bg-white/5 border border-purple-500/30
               px-4 py-2 rounded-xl text-white
               hover:bg-purple-600/20 transition">

        <span class="text-lg">🤖</span>
        <span class="font-medium">AI</span>

        <!-- icon mũi tên đẹp -->
        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- DROPDOWN -->
    <div id="aiDropdown"
         class="hidden absolute right-0 mt-3 w-56
                bg-black/95 backdrop-blur-xl
                border border-purple-500/20
                rounded-2xl shadow-2xl overflow-hidden">

        <!-- TITLE -->
        <div class="px-5 py-3 text-xs text-gray-400 border-b border-purple-500/10">
            AI MANAGEMENT
        </div>

        <!-- ITEM -->
        <a href="{{ route('admin.ai.settings') }}"
           class="flex items-center gap-3 px-5 py-3 
                  hover:bg-purple-600/20 transition">

            <span>⚙️</span>
            <span>Cài đặt AI</span>
        </a>

        <a href="{{ route('admin.ai.users') }}"
           class="flex items-center gap-3 px-5 py-3 
                  hover:bg-purple-600/20 transition">

            <span>💬</span>
            <span>Lịch sử chat</span>
        </a>

    </div>
</div>

</div>

</div>
        <!-- USER -->
        <div class="relative">

            <button id="userButton"
                class="flex items-center gap-3 bg-white/5 
                       px-4 py-2 rounded-xl border border-purple-500/30
                       hover:bg-purple-600/20 transition">

                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>

                <span>{{ auth()->user()->name }}</span>
            </button>

            <div id="userDropdown"
                 class="hidden absolute right-0 mt-3 w-52
                        bg-black/90 backdrop-blur-xl
                        border border-purple-500/30
                        rounded-xl shadow-xl">

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
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("aiMenuBtn");
    const dropdown = document.getElementById("aiDropdown");

    btn.addEventListener("click", function (e) {
        e.stopPropagation();
        dropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", function () {
        dropdown.classList.add("hidden");
    });
});
</script>