<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVote — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <style>
        .video-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            aspect-ratio: 4/3;
            margin: 0 auto;
            border-radius: 24px;
            overflow: hidden;
            background: #020617;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden">
    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-brand-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <nav class="sticky top-0 z-50 glass border-b border-white/5 mt-4 mx-4 md:mx-10 rounded-3xl">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">Secure<span class="text-brand-500">Vote</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="/register" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Register</a>
                <a href="/verify" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Verify & Vote</a>
                <a href="/register" class="btn-primary py-2.5 px-6 text-sm">Get Started</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-12 relative z-10">
        @yield('content')
    </main>

    <footer class="py-12 border-t border-slate-900 bg-slate-950/20">
        <div class="container mx-auto px-6 text-center">
            <div class="flex justify-center gap-4 mb-6">
                 <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center">
                     <span class="text-xs font-bold">11</span>
                 </div>
                 <span class="text-slate-500 font-medium">Built with Laravel 11 + Face-api.js</span>
            </div>
            <p class="text-slate-600 text-sm">
                &copy; {{ date('Y') }} SecureVote Global. All biometric data is encrypted and processed locally.
            </p>
        </div>
    </footer>
</body>
</html>
