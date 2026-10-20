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
                animation: gradientShift 12s ease infinite;
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
                33% { transform: translateY(-20px) scale(1.05); }
                66% { transform: translateY(10px) scale(0.95); }
            }

            /* Glass card */
            .glass-card {
                background: rgba(255, 255, 255, 0.06);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 25px 50px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            }

            /* Auth form card */
            .auth-card {
                background: rgba(255, 255, 255, 0.97);
                box-shadow: 0 32px 64px rgba(0,0,0,0.3), 0 2px 16px rgba(0,0,0,0.2);
            }

            /* Step flow */
            .step-dot {
                width: 8px; height: 8px;
                border-radius: 50%;
                background: rgba(255,255,255,0.4);
            }
            .step-dot.active { background: white; }
        </style>
    </head>
    <body class="antialiased">
        <div class="auth-bg min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-10">

            <!-- Floating Orbs -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <!-- Grid dots pattern overlay -->
            <div class="absolute inset-0 opacity-5"
                 style="background-image: radial-gradient(rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 w-full max-w-5xl mx-auto flex flex-col lg:flex-row items-center gap-8 lg:gap-16">

                <!-- Left Panel - Branding -->
                <div class="flex-1 text-white text-center lg:text-left space-y-8 max-w-md">
                    <!-- Logo -->
                    <div class="flex items-center gap-4 justify-center lg:justify-start">
                        <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur border border-white/20 flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight">RBAC Approval</h1>
                            <p class="text-indigo-300 text-sm font-medium">Sistem Persetujuan</p>
                        </div>
                    </div>

                    <!-- Tagline -->
                    <div>
                        <h2 class="text-3xl lg:text-4xl font-bold leading-tight">
                            Kelola Alur<br>
                            <span class="text-indigo-300">Persetujuan</span><br>
                            dengan Mudah
                        </h2>
                        <p class="mt-4 text-indigo-200/80 text-base leading-relaxed">
                            Sistem manajemen transaksi berbasis peran dengan alur kerja
                            <strong class="text-white">Maker → Checker → Approver</strong> yang terstruktur.
                        </p>
                    </div>

                    <!-- 3-step flow -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-4 glass-card rounded-xl px-4 py-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-500/40 border border-indigo-400/30 flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                            <div>
                                <p class="text-sm font-semibold">Inputer (Maker)</p>
                                <p class="text-xs text-indigo-300">Membuat & mengajukan transaksi baru</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 glass-card rounded-xl px-4 py-3">
                            <div class="w-9 h-9 rounded-lg bg-amber-500/30 border border-amber-400/30 flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                            <div>
                                <p class="text-sm font-semibold">Checker (Pemeriksa)</p>
                                <p class="text-xs text-indigo-300">Verifikasi kelayakan transaksi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 glass-card rounded-xl px-4 py-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/30 border border-emerald-400/30 flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                            <div>
                                <p class="text-sm font-semibold">Approver (Penyetuju)</p>
                                <p class="text-xs text-indigo-300">Keputusan akhir persetujuan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Auth Form -->
                <div class="w-full max-w-sm">
                    <div class="auth-card rounded-2xl p-8">
                        <!-- Form header -->
                        <div class="mb-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Selamat Datang</h3>
                            <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        {{ $slot }}
                    </div>

                    <!-- Footer note -->
                    <p class="text-center text-indigo-200/60 text-xs mt-6">
                        &copy; {{ date('Y') }} RBAC Approval System &mdash; Latihan Laravel
                    </p>
                </div>

            </div>
        </div>
    </body>
</html>
