<header class="flex justify-between items-center 
               px-10 py-6 border-b border-purple-500/30">

    <h2 class="text-xl font-semibold tracking-wide">
        Admin Panel
    </h2>

    <div class="relative">

        <!-- USER BUTTON -->
        <button id="userButton"
            class="flex items-center gap-3 bg-white/5 
                   px-4 py-2 rounded-xl border border-purple-500/30
                   hover:bg-purple-600/20 transition">

            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>

            <span>{{ auth()->user()->name }}</span>
        </button>

        <!-- DROPDOWN -->
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

</header>

<script>
document.getElementById('userButton')
    .addEventListener('click', function () {
        document.getElementById('userDropdown')
            .classList.toggle('hidden');
    });

document.addEventListener('click', function (e) {
    if (!e.target.closest('#userButton')) {
        document.getElementById('userDropdown')
            .classList.add('hidden');
    }
});
</script>