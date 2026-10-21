<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 - Akses Ditolak | RBAC Approval System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-900 text-slate-100 flex items-center justify-center p-4 antialiased">
        <div class="max-w-md w-full bg-slate-800/90 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-sm text-center">
            <!-- Icon 403 / Shield Warning -->
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <!-- Error Code & Title -->
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-rose-500/20 text-rose-300 border border-rose-500/30 mb-3">
                HTTP 403 Forbidden
            </span>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Akses Dibatasi</h1>
            
            <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                {{ $exception->getMessage() ?: 'Kamu tidak memiliki izin (permission) atau hak akses role yang sesuai untuk membuka halaman atau melakukan tindakan ini.' }}
            </p>

            @auth
            <!-- Info User & Role -->
            <div class="my-5 p-3.5 bg-slate-900/60 border border-slate-700/60 rounded-xl text-left text-xs">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-slate-400">Akun Login:</span>
                    <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Role Pengguna:</span>
                    <span class="font-semibold text-indigo-400">
                        {{ auth()->user()->getRoleNames()->join(', ') ?: 'Tidak ada role' }}
                    </span>
                </div>
            </div>
            @endauth

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row gap-2.5 mt-6">
                <a href="{{ route('dashboard') }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ke Dashboard
                </a>
                <a href="{{ route('rbac.index') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold rounded-xl border border-slate-600 transition-colors">
                    Lihat Hak Akses
                </a>
            </div>
        </div>
    </body>
</html>
