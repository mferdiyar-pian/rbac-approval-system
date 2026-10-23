<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RBAC Approval') }} — Sistem Persetujuan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }

            /* Animated gradient background */
            .auth-bg {
                background: radial-gradient(circle at 15% 25%, #2e1065 0%, #1e1b4b 35%, #0f172a 100%);
                min-height: 100vh;
            }

            /* Floating subtle orbs */
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(90px);
                opacity: 0.22;
                pointer-events: none;
                animation: floatOrb 10s ease-in-out infinite;
            }
            .orb-1 {
                width: 480px; height: 480px;
                background: #6366f1;
                top: -120px; left: -100px;
                animation-delay: 0s;
            }
            .orb-2 {
                width: 420px; height: 420px;
                background: #8b5cf6;
                bottom: -80px; right: -80px;
                animation-delay: 3.5s;
            }
            .orb-3 {
                width: 260px; height: 260px;
                background: #0ea5e9;
                top: 45%; left: 45%;
                transform: translate(-50%, -50%);
                animation-delay: 6s;
            }

            @keyframes floatOrb {
                0%, 100% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
            }

            /* Glass card styling */
            .glass-panel {
                background: rgba(255, 255, 255, 0.06);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.3);
            }

            /* Auth main card */
            .auth-card {
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.4);
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 selection:bg-indigo-500 selection:text-white">
        <div class="auth-bg min-h-screen flex flex-col justify-between relative overflow-hidden px-4 py-8 sm:py-10">

            <!-- Floating Orbs -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-[0.035] pointer-events-none"
                 style="background-image: radial-gradient(rgba(255,255,255,0.9) 1px, transparent 1px); background-size: 28px 28px;">
            </div>

            <!-- Top spacing anchor (keeps center aligned on large screens) -->
            <div class="hidden lg:block h-2"></div>

            <!-- Main Two-Column Layout -->
            <div class="relative z-10 w-full max-w-5xl mx-auto my-auto flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-14">

                <!-- Left Panel - Branding & Workflow -->
                <div class="flex-1 text-white text-center lg:text-left space-y-6 max-w-lg">
                    
                    <!-- Top Pill Tag -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 backdrop-blur-md text-xs font-semibold text-indigo-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Enterprise Role-Based Access Control</span>
                    </div>

                    <!-- Logo & Brand Header -->
                    <div class="flex items-center gap-3.5 justify-center lg:justify-start">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500/80 to-purple-600/80 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold tracking-tight text-white leading-tight">RBAC Approval</h1>
                            <p class="text-indigo-200 text-xs font-medium tracking-wide">Sistem Persetujuan Bertingkat</p>
                        </div>
                    </div>

                    <!-- Main Headline & Description -->
                    <div class="space-y-2">
                        <h2 class="text-2xl lg:text-3xl font-extrabold leading-snug tracking-tight text-white">
                            Kelola Alur Persetujuan<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-sky-200 to-white">
                                dengan Aman & Terstruktur
                            </span>
                        </h2>
                        <p class="text-indigo-200/85 text-xs sm:text-sm leading-relaxed">
                            Pemisahan wewenang operasional transaksi melalui mekanisme bertingkat
                            <span class="font-bold text-white">Maker &rarr; Checker &rarr; Approver</span> dilengkapi jejak audit otomatis.
                        </p>
                    </div>

                    <!-- 3-Step Interactive Workflow Pipeline -->
                    <div class="space-y-3 pt-1">
                        <!-- Step 1: Maker -->
                        <div class="glass-panel rounded-2xl p-3.5 flex items-center justify-between gap-3.5 transition-all duration-200 hover:translate-x-1 hover:border-indigo-400/40 hover:bg-white/10 group">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center flex-shrink-0 text-blue-300 font-bold text-sm shadow-inner group-hover:bg-blue-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="text-left overflow-hidden">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">1. Inputer (Maker)</span>
                                    </div>
                                    <p class="text-[11px] text-indigo-200/90 truncate">Membuat draft transaksi baru & mengajukan ke pemeriksa</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-blue-200 bg-blue-500/20 border border-blue-400/30 px-2 py-0.5 rounded-full flex-shrink-0">
                                Tahap 1
                            </span>
                        </div>

                        <!-- Step 2: Checker -->
                        <div class="glass-panel rounded-2xl p-3.5 flex items-center justify-between gap-3.5 transition-all duration-200 hover:translate-x-1 hover:border-amber-400/40 hover:bg-white/10 group">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0 text-amber-300 font-bold text-sm shadow-inner group-hover:bg-amber-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="text-left overflow-hidden">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">2. Checker (Pemeriksa)</span>
                                    </div>
                                    <p class="text-[11px] text-indigo-200/90 truncate">Verifikasi kelayakan data, nominal, dan kepatuhan transaksi</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-amber-200 bg-amber-500/20 border border-amber-400/30 px-2 py-0.5 rounded-full flex-shrink-0">
                                Tahap 2
                            </span>
                        </div>

                        <!-- Step 3: Approver -->
                        <div class="glass-panel rounded-2xl p-3.5 flex items-center justify-between gap-3.5 transition-all duration-200 hover:translate-x-1 hover:border-emerald-400/40 hover:bg-white/10 group">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center flex-shrink-0 text-emerald-300 font-bold text-sm shadow-inner group-hover:bg-emerald-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="text-left overflow-hidden">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">3. Approver (Penyetuju)</span>
                                    </div>
                                    <p class="text-[11px] text-indigo-200/90 truncate">Keputusan akhir otorisasi: Setujui (Approved) / Tolak (Rejected)</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-emerald-200 bg-emerald-500/20 border border-emerald-400/30 px-2 py-0.5 rounded-full flex-shrink-0">
                                Final
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Auth Card -->
                <div class="w-full max-w-md flex-shrink-0">
                    <div class="auth-card rounded-2xl p-6 sm:p-7 border border-white/50">
                        {{ $slot }}
                    </div>
                </div>

            </div>

            <!-- Global Clean Centered Footer -->
            <footer class="relative z-10 pt-6 pb-2 text-center text-indigo-200/60 text-xs">
                <p>&copy; {{ date('Y') }} <strong class="text-indigo-100 font-semibold">RBAC Approval System</strong> &mdash; Maker · Checker · Approver</p>
                <p class="text-[11px] text-indigo-300/50 mt-1">Implementasi Spatie Laravel Permission &amp; Otorisasi Bertingkat</p>
            </footer>

        </div>
    </body>
</html>
