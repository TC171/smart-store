<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Store Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .animate-enter {
            animation: fadeInUp 1s ease forwards;
        }

        .cyber-glow {
            box-shadow: 0 0 15px #9333ea, 0 0 30px #6366f1;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-black overflow-hidden relative">

<!-- PARTICLE BACKGROUND -->
<canvas id="particles" class="absolute inset-0 z-0"></canvas>

<!-- Glow circles -->
<div class="absolute w-[600px] h-[600px] bg-purple-700 rounded-full blur-[200px] opacity-30 -top-40 -left-40 animate-[float_6s_ease-in-out_infinite]"></div>
<div class="absolute w-[500px] h-[500px] bg-blue-600 rounded-full blur-[200px] opacity-30 bottom-0 right-0 animate-[float_8s_ease-in-out_infinite]"></div>

<div class="relative z-10 w-full max-w-md px-6 animate-enter">

    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-white tracking-widest">
            SMART STORE
        </h1>
        <p class="text-gray-400 mt-2 text-sm">
            Cyberpunk Admin Panel
        </p>
    </div>

    <div class="bg-white/5 backdrop-blur-xl border border-purple-500/30 
                rounded-3xl p-8 shadow-2xl cyber-glow">

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-600/80 text-white text-sm rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" 
              class="space-y-6" id="loginForm">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="block text-gray-300 text-sm mb-2">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-black/50 text-white
                              border border-gray-700
                              focus:outline-none focus:ring-2 focus:ring-purple-500
                              focus:shadow-[0_0_20px_#9333ea]
                              transition duration-300"
                       placeholder="admin@gmail.com">
            </div>

            <!-- PASSWORD -->
            <div class="relative">
                <label class="block text-gray-300 text-sm mb-2">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-3 rounded-xl bg-black/50 text-white
                              border border-gray-700
                              focus:outline-none focus:ring-2 focus:ring-purple-500
                              focus:shadow-[0_0_20px_#9333ea]
                              transition duration-300"
                       placeholder="••••••••">

                <!-- Toggle -->
                <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-10 text-gray-400 hover:text-purple-400 transition">
                    👁
                </button>
            </div>

            <!-- BUTTON -->
            <button type="submit" id="submitBtn"
                    class="w-full py-3 rounded-xl bg-gradient-to-r 
                           from-purple-600 to-indigo-600
                           text-white font-semibold
                           hover:scale-[1.03] active:scale-95
                           transition duration-200 shadow-lg
                           flex items-center justify-center gap-2">
                <span id="btnText">Sign In</span>
                <svg id="spinner" class="w-5 h-5 animate-spin hidden" 
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" 
                            stroke="white" stroke-width="4"></circle>
                    <path class="opacity-75" fill="white"
                          d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>

        </form>
    </div>

    <p class="text-center text-gray-500 text-xs mt-8">
        © {{ date('Y') }} Smart Store
    </p>

</div>

<script>
    // SHOW / HIDE PASSWORD
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // LOADING SPINNER
    document.getElementById('loginForm').addEventListener('submit', function() {
        document.getElementById('btnText').innerText = "Signing...";
        document.getElementById('spinner').classList.remove('hidden');
    });

    // PARTICLES
    const canvas = document.getElementById("particles");
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let particles = [];

    for (let i = 0; i < 80; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 2,
            dx: (Math.random() - 0.5) * 0.5,
            dy: (Math.random() - 0.5) * 0.5
        });
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = "rgba(147, 51, 234, 0.7)";

        particles.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fill();

            p.x += p.dx;
            p.y += p.dy;

            if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
        });

        requestAnimationFrame(animate);
    }

    animate();
</script>

</body>
</html>