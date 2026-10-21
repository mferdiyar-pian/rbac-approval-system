<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RBAC Approval') }} — Sistem Persetujuan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }

            /* Animated gradient background */
            .auth-bg {
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #3730a3 50%, #4338ca 75%, #1e1b4b 100%);
                background-size: 400% 400%;
                animation: gradientShift 14s ease infinite;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Floating orbs */
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.15;
                animation: float 8s ease-in-out infinite;
            }
            .orb-1 {
                width: 400px; height: 400px;
                background: #818cf8;
                top: -100px; left: -100px;
                animation-delay: 0s;
            }
            .orb-2 {
                width: 300px; height: 300px;
                background: #a78bfa;
                bottom: -50px; right: -50px;
                animation-delay: 3s;
            }
            .orb-3 {
                width: 200px; height: 200px;
                background: #60a5fa;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                animation-delay: 6s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) scale(1); }
                33% { transform: translateY(-16px) scale(1.04); }
                66% { transform: translateY(8px) scale(0.96); }
            }

            /* Glass card */
            .glass-card {
                background: rgba(255, 255, 255, 0.07);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 16px 32px rgba(0,0,0,0.25);
            }

            /* Auth form card */
            .auth-card {
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 selection:bg-indigo-500 selection:text-white">
        <div class="auth-bg min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-8 sm:py-10">

            <!-- Floating Orbs -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <!-- Grid dots pattern overlay -->
            <div class="absolute inset-0 opacity-5 pointer-events-none"
                 style="background-image: radial-gradient(rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 w-full max-w-5xl mx-auto flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-14">

                <!-- Left Panel - Branding -->
                <div class="flex-1 text-white text-center lg:text-left space-y-6 max-w-md">
                    <!-- Logo & Brand Header -->
                    <div class="flex items-center gap-3.5 justify-center lg:justify-start">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur border border-white/25 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-white leading-tight">RBAC Approval</h1>
                            <p class="text-indigo-200 text-xs font-medium">Sistem Persetujuan Bertingkat</p>
                        </div>
                    </div>

                    <!-- Tagline -->
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold leading-snug">
                            Kelola Alur Persetujuan<br>
                            <span class="text-indigo-300">dengan Aman & Terstruktur</span>
                        </h2>
                        <p class="mt-2.5 text-indigo-100/80 text-xs sm:text-sm leading-relaxed">
                            Sistem manajemen transaksi berbasis peran dengan implementasi alur kerja
                            <strong class="text-white">Maker &rarr; Checker &rarr; Approver</strong> dan pencatatan audit log permanen.
                        </p>
                    </div>

                    <!-- 3-step flow cards -->
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-3.5 glass-card rounded-xl px-4 py-2.5 transition-transform hover:translate-x-1 duration-150">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/40 border border-indigo-400/40 flex items-center justify-center text-xs font-bold flex-shrink-0 text-white">1</div>
                            <div class="text-left">
                                <p class="text-xs font-bold text-white">Inputer (Maker)</p>
                                <p class="text-[11px] text-indigo-200">Membuat dan mengajukan draft transaksi baru</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 glass-card rounded-xl px-4 py-2.5 transition-transform hover:translate-x-1 duration-150">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/40 border border-amber-400/40 flex items-center justify-center text-xs font-bold flex-shrink-0 text-white">2</div>
                            <div class="text-left">
                                <p class="text-xs font-bold text-white">Checker (Pemeriksa)</p>
                                <p class="text-[11px] text-indigo-200">Verifikasi kelayakan dan kelengkapan transaksi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 glass-card rounded-xl px-4 py-2.5 transition-transform hover:translate-x-1 duration-150">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/40 border border-emerald-400/40 flex items-center justify-center text-xs font-bold flex-shrink-0 text-white">3</div>
                            <div class="text-left">
                                <p class="text-xs font-bold text-white">Approver (Penyetuju)</p>
                                <p class="text-[11px] text-indigo-200">Keputusan akhir persetujuan (Setujui / Tolak)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Auth Card -->
                <div class="w-full max-w-md">
                    <div class="auth-card rounded-2xl p-6 sm:p-7 border border-white/40">
                        {{ $slot }}
                    </div>

                    <!-- Footer note -->
                    <p class="text-center text-indigo-200/70 text-xs mt-4">
                        &copy; {{ date('Y') }} RBAC Approval System &mdash; Hak Akses Berbasis Peran
                    </p>
                </div>

            </div>
        </div>
    </body>
</html>
