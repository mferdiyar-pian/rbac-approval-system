<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-0.5">Selamat datang di RBAC Approval System</p>
    </x-slot>

    <!-- Welcome Card -->
    <div class="mb-6">
        <div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <!-- Background decorative circles -->
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 right-12 w-32 h-32 bg-white/5 rounded-full translate-y-1/2"></div>

            <div class="relative flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-indigo-200 text-sm">Selamat datang kembali,</p>
                        <h1 class="text-xl font-bold">{{ Auth::user()->name }}</h1>
                        <p class="text-indigo-200 text-sm mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach(Auth::user()->getRoleNames() as $roleName)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/15 border border-white/20 rounded-xl text-xs font-semibold">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $roleName }}
                    </span>
                    @endforeach
                </div>
            </div>

            <!-- Stats strip -->
            @php
                try {
                    $u = auth()->user();
                    // Hanya batasi jika user adalah murni Inputer (bukan Superadmin, Admin, Checker, atau Approver)
                    $isInputerOnly = $u->hasRole('Inputer') && !$u->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']);

                    $totalTx = \App\Models\Transaction::when($isInputerOnly, fn($q) => $q->where('inputer_id', $u->id))->count();
                    $draftTx = \App\Models\Transaction::when($isInputerOnly, fn($q) => $q->where('inputer_id', $u->id))->where('status', 'draft')->count();
                    $pendingTx = \App\Models\Transaction::when($isInputerOnly, fn($q) => $q->where('inputer_id', $u->id))->whereIn('status', ['pending_check', 'pending_approval'])->count();
                    $approvedTx = \App\Models\Transaction::when($isInputerOnly, fn($q) => $q->where('inputer_id', $u->id))->where('status', 'approved')->count();
                } catch(\Exception $e) {
                    $totalTx = $draftTx = $pendingTx = $approvedTx = 0;
                }
            @endphp
            <div class="relative mt-5 grid grid-cols-4 gap-3 border-t border-white/15 pt-4">
                <div class="text-center">
                    <p class="text-2xl font-bold">{{ $totalTx }}</p>
                    <p class="text-indigo-200 text-[11px] font-medium mt-0.5">Total Transaksi</p>
                </div>
                <div class="text-center border-l border-white/15">
                    <p class="text-2xl font-bold">{{ $draftTx }}</p>
                    <p class="text-indigo-200 text-[11px] font-medium mt-0.5">Draft</p>
                </div>
                <div class="text-center border-l border-white/15">
                    <p class="text-2xl font-bold text-amber-300">{{ $pendingTx }}</p>
                    <p class="text-indigo-200 text-[11px] font-medium mt-0.5">Menunggu</p>
                </div>
                <div class="text-center border-l border-white/15">
                    <p class="text-2xl font-bold text-emerald-300">{{ $approvedTx }}</p>
                    <p class="text-indigo-200 text-[11px] font-medium mt-0.5">Disetujui</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alur Approval & Akun Latihan -->
    <div class="mb-6 bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                Alur Kerja Sistem Persetujuan (Maker - Checker - Approver)
            </h2>
            <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-1 rounded-full">Sistem RBAC 3-Tahap</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="p-3 rounded-xl border {{ auth()->user()->hasRole('Inputer') ? 'bg-indigo-50/70 border-indigo-300 ring-2 ring-indigo-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between font-bold mb-1">
                    <span class="text-indigo-700">1. Inputer (Maker)</span>
                    @if(auth()->user()->hasRole('Inputer'))<span class="text-[10px] bg-indigo-600 text-white px-1.5 py-0.5 rounded">Kamu Di Sini</span>@endif
                </div>
                <p class="text-gray-600">Membuat transaksi baru (Draft) dan mengajukan ke Checker.</p>
                <p class="text-gray-400 mt-2 font-mono text-[11px]">inputer@example.com</p>
            </div>
            <div class="p-3 rounded-xl border {{ auth()->user()->hasRole('Checker') ? 'bg-amber-50/70 border-amber-300 ring-2 ring-amber-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between font-bold mb-1">
                    <span class="text-amber-700">2. Checker (Pemeriksa)</span>
                    @if(auth()->user()->hasRole('Checker'))<span class="text-[10px] bg-amber-600 text-white px-1.5 py-0.5 rounded">Kamu Di Sini</span>@endif
                </div>
                <p class="text-gray-600">Memeriksa kelayakan data. Jika lolos, transaksi diteruskan ke Approver.</p>
                <p class="text-gray-400 mt-2 font-mono text-[11px]">checker@example.com</p>
            </div>
            <div class="p-3 rounded-xl border {{ auth()->user()->hasRole('Approver') ? 'bg-emerald-50/70 border-emerald-300 ring-2 ring-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between font-bold mb-1">
                    <span class="text-emerald-700">3. Approver (Penyetuju)</span>
                    @if(auth()->user()->hasRole('Approver'))<span class="text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded">Kamu Di Sini</span>@endif
                </div>
                <p class="text-gray-600">Memberikan persetujuan akhir (Disetujui) atau menolak transaksi.</p>
                <p class="text-gray-400 mt-2 font-mono text-[11px]">approver@example.com</p>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Card: Info Role -->
        <a href="{{ route('rbac.index') }}"
           class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-purple-700 transition-colors">Info Role & Akses</h3>
                    <p class="text-sm text-gray-500 mt-1">Lihat role dan permission yang dimiliki akun kamu</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-purple-600 text-sm font-medium">
                Lihat Detail
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        @can('view transactions')
        <!-- Card: Daftar Transaksi -->
        <a href="{{ route('transactions.index') }}"
           class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-blue-700 transition-colors">
                        @if(auth()->user()->hasRole('Inputer') && !auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                            Transaksi Saya
                        @else
                            Daftar Transaksi
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(auth()->user()->hasRole('Inputer') && !auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                            Lihat semua transaksi yang telah kamu buat
                        @else
                            Pantau semua daftar transaksi dalam sistem
                        @endif
                    </p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-blue-600 text-sm font-medium">
                Lihat Transaksi
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

        @can('create transactions')
        <!-- Card: Input Transaksi -->
        <a href="{{ route('transactions.create') }}"
           class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors">Input Transaksi Baru</h3>
                    <p class="text-sm text-gray-500 mt-1">Buat transaksi baru sebagai draft</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-green-600 text-sm font-medium">
                Buat Transaksi
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

        @can('check transactions')
        <!-- Card: Pemeriksaan Checker -->
        <a href="{{ route('transactions.checker') }}"
           class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-amber-700 transition-colors">Pemeriksaan (Checker)</h3>
                    <p class="text-sm text-gray-500 mt-1">Periksa transaksi yang menunggu persetujuan</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-amber-600 text-sm font-medium">
                Mulai Periksa
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

        @can('approve transactions')
        <!-- Card: Persetujuan Approver -->
        <a href="{{ route('transactions.approver') }}"
           class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-emerald-700 transition-colors">Persetujuan (Approver)</h3>
                    <p class="text-sm text-gray-500 mt-1">Setujui atau tolak transaksi dari Checker</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-emerald-600 text-sm font-medium">
                Mulai Setujui
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

        @can('manage users')
        <!-- Card: Kelola Pengguna (Superadmin) -->
        <a href="{{ route('users.index') }}"
           class="bg-white rounded-xl p-5 border border-indigo-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group bg-gradient-to-br from-white to-indigo-50/30">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors">Kelola Pengguna</h3>
                        <span class="text-[10px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded font-bold">Admin</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Tambah akun, edit profil, ganti role & password</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium">
                Buka Manajemen Akun
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

        @can('view audit logs')
        <!-- Card: Log Audit (Superadmin / Admin) -->
        <a href="{{ route('audit-logs.index') }}"
           class="bg-white rounded-xl p-5 border border-purple-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group bg-gradient-to-br from-white to-purple-50/30">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-gray-800 group-hover:text-purple-700 transition-colors">Log Audit</h3>
                        <span class="text-[10px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded font-bold">Audit Trail</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Pantau rekam jejak CCTV aktivitas & perubahan data</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-purple-600 text-sm font-medium">
                Buka Log Audit
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endcan

    </div>
</x-app-layout>
