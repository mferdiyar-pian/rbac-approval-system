<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RBAC Approval') }} — @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }

            /* Sidebar gradient */
            .sidebar {
                background: linear-gradient(180deg, #1e1b4b 0%, #312e81 40%, #2d3a6e 100%);
            }

            /* Active nav item glow */
            .nav-active {
                background: rgba(255,255,255,0.15);
                color: white;
                box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
            }

            /* Scrollbar thin */
            .sidebar-nav::-webkit-scrollbar { width: 4px; }
            .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
            .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

            /* Mobile overlay */
            #sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                z-index: 30;
            }
            #sidebar-overlay.active { display: block; }

            @media (max-width: 1024px) {
                #sidebar {
                    position: fixed;
                    left: -280px;
                    top: 0;
                    bottom: 0;
                    z-index: 40;
                    transition: left 0.3s ease;
                    width: 260px !important;
                }
                #sidebar.open { left: 0; }
                #mobile-header { display: flex !important; }
                #main-content { margin-left: 0 !important; }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">

        <!-- Mobile overlay -->
        <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Mobile Top Header -->
        <header id="mobile-header"
            class="hidden items-center justify-between bg-white border-b border-gray-200 px-4 py-3 lg:hidden sticky top-0 z-20 shadow-sm">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-gray-800">RBAC Approval</span>
            </div>
            <div class="w-9 h-9 rounded-full bg-indigo-700 flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </header>

        <!-- Sidebar + Main Layout -->
        <div class="flex min-h-screen">

            <!-- Sidebar -->
            <aside id="sidebar" class="sidebar w-64 text-white flex flex-col shadow-2xl flex-shrink-0">

                <!-- Logo / Brand -->
                <div class="px-5 py-5 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/15 rounded-xl border border-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-white font-bold text-sm leading-tight">RBAC Approval</p>
                            <p class="text-indigo-300 text-xs">Sistem Persetujuan</p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="px-4 py-3.5 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden flex-1">
                            <p class="text-white text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-indigo-300 text-[11px] truncate">
                                @php
                                    $role = Auth::user()->getRoleNames()->first() ?? 'Pengguna';
                                @endphp
                                {{ $role }}
                            </p>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0" title="Online"></div>
                    </div>
                </div>

                <!-- Nav Links -->
                <nav class="flex-1 px-3 py-4 space-y-0.5 sidebar-nav overflow-y-auto">

                    <!-- Main Menu -->
                    <p class="text-indigo-400/70 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2">Menu Utama</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('rbac.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('rbac.index') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('rbac.index') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Info Role & Akses</span>
                    </a>

                    @canany(['view transactions', 'create transactions', 'check transactions', 'approve transactions'])
                    <!-- Divider -->
                    <div class="pt-3 pb-1">
                        <p class="text-indigo-400/70 text-[10px] font-semibold uppercase tracking-widest px-3">Transaksi</p>
                    </div>
                    @endcanany

                    @can('view transactions')
                    <a href="{{ route('transactions.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('transactions.index*') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('transactions.index*') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>
                            @if(auth()->user()->hasRole('Inputer') && !auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                                Transaksi Saya
                            @else
                                Semua Transaksi
                            @endif
                        </span>
                    </a>
                    @endcan

                    @can('create transactions')
                    <a href="{{ route('transactions.create') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('transactions.create') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('transactions.create') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Input Transaksi Baru</span>
                    </a>
                    @endcan

                    @can('check transactions')
                    <a href="{{ route('transactions.checker') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('transactions.checker') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('transactions.checker') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Pemeriksaan (Checker)</span>
                        @php
                            try { $pendingCheckCount = \App\Models\Transaction::where('status', 'pending_check')->count(); } catch(\Exception $e) { $pendingCheckCount = 0; }
                        @endphp
                        @if($pendingCheckCount > 0)
                        <span class="ml-auto min-w-[18px] h-[18px] bg-amber-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1">
                            {{ $pendingCheckCount > 9 ? '9+' : $pendingCheckCount }}
                        </span>
                        @endif
                    </a>
                    @endcan

                    @can('approve transactions')
                    <a href="{{ route('transactions.approver') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('transactions.approver') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('transactions.approver') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Persetujuan (Approver)</span>
                        @php
                            try { $pendingApprovalCount = \App\Models\Transaction::where('status', 'pending_approval')->count(); } catch(\Exception $e) { $pendingApprovalCount = 0; }
                        @endphp
                        @if($pendingApprovalCount > 0)
                        <span class="ml-auto min-w-[18px] h-[18px] bg-blue-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1">
                            {{ $pendingApprovalCount > 9 ? '9+' : $pendingApprovalCount }}
                        </span>
                        @endif
                    </a>
                    @endcan

                    @canany(['manage users', 'view audit logs'])
                    <div class="pt-3 pb-1">
                        <p class="text-indigo-400/70 text-[10px] font-semibold uppercase tracking-widest px-3">Administrator</p>
                    </div>
                    @endcanany

                    @can('manage users')
                    <a href="{{ route('users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('users.*') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Kelola Pengguna</span>
                    </a>
                    @endcan

                    @can('view audit logs')
                    <a href="{{ route('audit-logs.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all duration-150 group
                              {{ request()->routeIs('audit-logs.*') ? 'nav-active' : 'text-indigo-200 hover:bg-white/8 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('audit-logs.*') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Log Audit</span>
                    </a>
                    @endcan
                </nav>

                <!-- Bottom Actions -->
                <div class="px-3 py-3 border-t border-white/10 space-y-1">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-indigo-300 hover:bg-white/8 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-red-300/80 hover:bg-red-500/15 hover:text-red-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div id="main-content" class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                @isset($header)
                <header class="bg-white border-b border-gray-100 px-6 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                    <div>
                        {{ $header }}
                    </div>
                    <div class="hidden lg:flex items-center gap-3 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="clock-display">{{ now()->setTimezone('Asia/Makassar')->format('d M Y, H:i') }} WITA</span>
                    </div>
                </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 p-6 lg:p-8">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="px-6 lg:px-8 py-3 border-t border-gray-100 bg-white">
                    <p class="text-xs text-gray-400 text-center">
                        &copy; {{ date('Y') }} <strong class="text-gray-500">RBAC Approval System</strong> &mdash; Maker · Checker · Approver
                    </p>
                </footer>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            }

            // Live clock WITA (Asia/Makassar, UTC+8)
            function updateClock() {
                const el = document.getElementById('clock-display');
                if (!el) return;
                try {
                    const now = new Date();
                    const formatter = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Makassar',
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                    const parts = formatter.formatToParts(now);
                    const get = (type) => parts.find(p => p.type === type)?.value || '';
                    el.textContent = `${get('day')} ${get('month')} ${get('year')}, ${get('hour')}:${get('minute')}:${get('second')} WITA`;
                } catch (e) {
                    const now = new Date();
                    el.textContent = now.toLocaleTimeString('id-ID') + ' WITA';
                }
            }
            updateClock();
            setInterval(updateClock, 1000);
        </script>
    </body>
</html>
