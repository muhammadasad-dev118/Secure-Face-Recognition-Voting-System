@extends('layouts.app')

@section('title', 'Empowering Democracy')

@section('content')
<div class="relative py-20 lg:py-32">
    <div class="text-center max-w-5xl mx-auto space-y-10 group">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-dark border border-brand-500/20 text-brand-400 text-sm font-bold uppercase tracking-widest animate-fade-in">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
            </span>
            Next-Gen Biometric Voting
        </div>

        <h1 class="text-7xl md:text-8xl font-black tracking-tighter leading-[0.9] bg-clip-text text-transparent bg-gradient-to-b from-white via-white to-slate-500">
            Secure Your Vote <br class="hidden md:block"> With Your <span class="text-brand-500 italic">Face.</span>
        </h1>

        <p class="text-xl md:text-2xl text-slate-400 max-w-3xl mx-auto font-medium leading-relaxed">
            Eliminate voter fraud and ensure transparency with our state-of-the-art 128-bit facial encryption. Simple, secure, and instantaneous.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-10">
            <a href="/register" class="btn-primary w-full sm:w-auto text-xl px-12 py-5 shadow-[0_20px_50px_-15px_oklch(0.55_0.21_254)]">
                Enroll My Identity
            </a>
            <a href="/verify" class="btn-secondary w-full sm:w-auto text-xl px-12 py-5">
                Cast Ballot
            </a>
        </div>

        <!-- Trust Section -->
        <div class="pt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-dark p-10 rounded-[2.5rem] border border-white/5 space-y-4 hover:-translate-y-2 transition-transform duration-500">
                <div class="w-14 h-14 bg-brand-600/10 rounded-2xl flex items-center justify-center text-brand-500 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20a10.003 10.003 0 006.203-2.138l.054.09a10.003 10.003 0 01-2.753-9.571m-2.753-1.429A9.9a9.9 0 00-2.753 1.429m2.753 0A9.9 9.9 0 0112 11z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold">Biometric ID</h3>
                <p class="text-slate-400 font-medium">Unique facial landmarks mapped and hashed for identity anchoring.</p>
            </div>

            <div class="glass-dark p-10 rounded-[2.5rem] border border-white/5 space-y-4 hover:-translate-y-2 transition-transform duration-500">
                <div class="w-14 h-14 bg-purple-600/10 rounded-2xl flex items-center justify-center text-purple-500 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold">Liveness Core</h3>
                <p class="text-slate-400 font-medium">Blink detection engine monitors for physical presence in real-time.</p>
            </div>

            <div class="glass-dark p-10 rounded-[2.5rem] border border-white/5 space-y-4 hover:-translate-y-2 transition-transform duration-500">
                <div class="w-14 h-14 bg-cyan-600/10 rounded-2xl flex items-center justify-center text-cyan-500 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold">Atomic Safety</h3>
                <p class="text-slate-400 font-medium">Database-level transactions guarantee one vote per verified user.</p>
            </div>
        </div>
    </div>
</div>
@endsection
