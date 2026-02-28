<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Store Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="bg-black text-white overflow-hidden relative">

<!-- PARTICLE BACKGROUND -->
<canvas id="particles" class="absolute inset-0 z-0"></canvas>

<!-- Glow effects -->
<div class="absolute w-[700px] h-[700px] bg-purple-700 rounded-full blur-[200px] opacity-20 -top-60 -left-60 animate-[float_8s_ease-in-out_infinite]"></div>
<div class="absolute w-[600px] h-[600px] bg-blue-600 rounded-full blur-[200px] opacity-20 bottom-0 right-0 animate-[float_10s_ease-in-out_infinite]"></div>

<div class="relative z-10 flex h-screen">

    @include('admin.partials.sidebar')

    <div class="flex-1 flex flex-col overflow-y-auto">
        @include('admin.partials.header')

        <main class="p-10">
            @yield('content')
        </main>
    </div>

</div>

<script>
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];

for (let i = 0; i < 100; i++) {
    particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() * 2,
        dx: (Math.random() - 0.5) * 0.4,
        dy: (Math.random() - 0.5) * 0.4
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