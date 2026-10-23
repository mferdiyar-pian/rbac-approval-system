<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Log Audit Sistem</h2>
                <p class="text-sm text-gray-500 mt-0.5">Rekam jejak otomatis seluruh aktivitas dan perubahan data dalam sistem</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold rounded-full">
                <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                Append-Only · Tidak Dapat Dihapus
            </span>
        </div>
    </x-slot>

    {{-- Info Banner --}}
    <div class="mb-6 bg-white border border-purple-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-gray-800">Apa itu Log Audit & Mengapa Dicatat?</h3>
                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                    Log audit adalah <strong>CCTV digital</strong> yang mencatat setiap peristiwa penting: <strong>Siapa</strong> yang melakukannya, <strong>Kapan</strong> waktunya (WITA), <strong>Apa</strong> yang diubah, dan dari <strong>IP Address</strong> mana.
                    Log ini dilindungi fitur <em>Append-Only (Immutable)</em> — tidak dapat diedit atau dihapus oleh siapapun demi menjaga transparansi dan integritas audit.
                </p>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="mb-5 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <form action="{{ route('audit-logs.index') }}" method="GET">
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <select name="action" onchange="this.form.submit()"
                    class="px-3 py-2.5 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-700 bg-white min-w-[200px]">
                    <option value="">— Semua Kategori Aksi —</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                            {{ $act }}
                        </option>
                    @endforeach
                </select>

                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan deskripsi, nama user, atau IP..."
                        class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-800 placeholder:text-gray-400">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                        Cari
                    </button>
                    @if (request()->hasAny(['action', 'search']))
                        <a href="{{ route('audit-logs.index') }}"
                           class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Waktu (WITA)</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Aktivitas</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">IP Address</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    @php
                        $actionBadge = match(true) {
                            str_contains($log->action, 'APPROVE')  => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
                            str_contains($log->action, 'CHECK')    => ['bg' => 'bg-teal-50 text-teal-800 border-teal-200',     'dot' => 'bg-teal-500'],
                            str_contains($log->action, 'CREATE')   => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200',      'dot' => 'bg-blue-500'],
                            str_contains($log->action, 'SUBMIT')   => ['bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200','dot' => 'bg-indigo-500'],
                            str_contains($log->action, 'LOGIN')    => ['bg' => 'bg-sky-50 text-sky-800 border-sky-200',         'dot' => 'bg-sky-500'],
                            str_contains($log->action, 'LOGOUT')   => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200',  'dot' => 'bg-slate-400'],
                            str_contains($log->action, 'EDIT') || str_contains($log->action, 'CORRECT') || str_contains($log->action, 'OVERRIDE')
                                                                    => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200',  'dot' => 'bg-amber-500'],
                            str_contains($log->action, 'REJECT') || str_contains($log->action, 'DELETE')
                                                                    => ['bg' => 'bg-red-50 text-red-800 border-red-200',        'dot' => 'bg-red-500'],
                            default                                 => ['bg' => 'bg-gray-100 text-gray-700 border-gray-200',    'dot' => 'bg-gray-400'],
                        };

                        // ── Deteksi Browser, OS, Device dari User-Agent ──
                        $ua      = $log->user_agent ?? '';
                        $browser = 'Tidak Diketahui';
                        $os      = 'Tidak Diketahui';
                        $device  = 'Desktop';

                        if (str_contains($ua, 'Edg/') || str_contains($ua, 'EdgA/'))          $browser = 'Microsoft Edge';
                        elseif (str_contains($ua, 'OPR/') || str_contains($ua, 'Opera'))       $browser = 'Opera';
                        elseif (str_contains($ua, 'Firefox/'))                                  $browser = 'Mozilla Firefox';
                        elseif (str_contains($ua, 'Chrome/'))                                   $browser = 'Google Chrome';
                        elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome'))  $browser = 'Apple Safari';
                        elseif ($ua === 'CLI/Browser')                                          $browser = 'CLI / Artisan (Server)';

                        if (str_contains($ua, 'Windows NT 10.0'))       $os = 'Windows 10 / 11';
                        elseif (str_contains($ua, 'Windows NT 6.3'))     $os = 'Windows 8.1';
                        elseif (str_contains($ua, 'Windows NT 6.1'))     $os = 'Windows 7';
                        elseif (str_contains($ua, 'Macintosh'))          $os = 'macOS';
                        elseif (str_contains($ua, 'Android'))            $os = 'Android';
                        elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) $os = 'iOS';
                        elseif (str_contains($ua, 'Linux'))              $os = 'Linux';

                        if (str_contains($ua, 'Mobile') || str_contains($ua, 'Android') || str_contains($ua, 'iPhone'))
                            $device = 'Mobile';
                        elseif (str_contains($ua, 'iPad'))
                            $device = 'Tablet';
                    @endphp

                    {{-- Baris tabel — klik baris = buka modal --}}
                    <tr class="hover:bg-purple-50/30 transition-colors cursor-pointer group"
                        onclick="openAuditModal({{ $log->id }})">

                        {{-- Waktu --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-gray-800 block">{{ $log->created_at->format('d M Y') }}</span>
                            <span class="text-[11px] text-gray-500 font-mono">{{ $log->created_at->format('H:i:s') }}</span>
                            <span class="text-[10px] text-gray-400 block">{{ $log->created_at->diffForHumans() }}</span>
                        </td>

                        {{-- Pengguna --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">{{ $log->user_name }}</p>
                                    <span class="inline-block text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded font-medium mt-0.5">{{ $log->user_role }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $actionBadge['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $actionBadge['dot'] }}"></span>
                                {{ $log->action }}
                            </span>
                        </td>

                        {{-- Deskripsi --}}
                        <td class="px-5 py-4 text-xs text-gray-700 max-w-sm">
                            <p class="leading-relaxed">{{ $log->description }}</p>
                        </td>

                        {{-- IP Address --}}
                        <td class="px-5 py-4 whitespace-nowrap text-xs">
                            <span class="font-mono text-[11px] bg-gray-100 text-gray-700 px-2 py-0.5 rounded block w-fit mb-1">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </span>
                            <span class="text-[10px] text-gray-400 truncate max-w-[140px] block" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 22) }}
                            </span>
                        </td>

                        {{-- Tombol Detail --}}
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <button type="button"
                                onclick="openAuditModal({{ $log->id }}); event.stopPropagation();"
                                id="btn-detail-{{ $log->id }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition-all shadow-sm group-hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </button>
                        </td>
                    </tr>

                    {{-- Template data modal untuk baris ini (tersembunyi, dibaca oleh JS) --}}
                    <template id="modal-data-{{ $log->id }}">
                        {{-- Badge ID --}}
                        <div class="text-[10px] font-mono text-purple-500 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded w-fit mb-4">
                            Log ID #{{ $log->id }}
                        </div>

                        {{-- Grid: Waktu & Pengguna --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-100">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Waktu Kejadian
                                </p>
                                <p class="text-sm font-bold text-gray-800">{{ $log->created_at->format('d M Y') }}</p>
                                <p class="text-xs font-mono text-gray-600">{{ $log->created_at->format('H:i:s') }} WITA</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </div>

                            <div class="bg-indigo-50 rounded-xl p-3.5 border border-indigo-100">
                                <p class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Pelaku Aksi
                                </p>
                                <p class="text-sm font-bold text-gray-800">{{ $log->user_name }}</p>
                                <p class="text-xs text-indigo-600 font-semibold">Role: {{ $log->user_role }}</p>
                                @if($log->user_id)
                                    <p class="text-[10px] text-gray-400 mt-0.5">User ID: #{{ $log->user_id }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Kode Aksi --}}
                        <div class="mb-3 bg-white rounded-xl p-3.5 border border-gray-200">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 10V5a2 2 0 012-2z"/>
                                </svg>
                                Kode Aksi
                            </p>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $actionBadge['bg'] }}">
                                <span class="w-2 h-2 rounded-full {{ $actionBadge['dot'] }}"></span>
                                {{ $log->action }}
                            </span>
                        </div>

                        {{-- Deskripsi Lengkap --}}
                        <div class="mb-3 bg-white rounded-xl p-3.5 border border-gray-200">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Deskripsi Aktivitas Lengkap
                            </p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $log->description }}</p>
                        </div>

                        {{-- Informasi Perangkat & Jaringan --}}
                        <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200">
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                                </svg>
                                Informasi Perangkat & Jaringan
                            </p>

                            {{-- Grid 3 kolom: IP, Browser, OS --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                                <div class="bg-white rounded-lg p-3 border border-slate-200">
                                    <p class="text-[9px] text-slate-400 font-semibold uppercase mb-1">IP Address</p>
                                    <p class="text-xs font-mono font-bold text-gray-800">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </p>
                                    @if(($log->ip_address ?? '127.0.0.1') === '127.0.0.1')
                                        <p class="text-[9px] text-slate-400 mt-1">📍 Localhost — akses dari komputer lokal (bukan internet)</p>
                                    @endif
                                </div>

                                <div class="bg-white rounded-lg p-3 border border-slate-200">
                                    <p class="text-[9px] text-slate-400 font-semibold uppercase mb-1">Browser</p>
                                    <p class="text-xs font-bold text-gray-800">{{ $browser }}</p>
                                    <p class="text-[9px] text-slate-400 mt-0.5">dideteksi dari User-Agent</p>
                                </div>

                                <div class="bg-white rounded-lg p-3 border border-slate-200">
                                    <p class="text-[9px] text-slate-400 font-semibold uppercase mb-1">Sistem Operasi</p>
                                    <p class="text-xs font-bold text-gray-800">{{ $os }}</p>
                                    <p class="text-[9px] text-slate-400 mt-0.5">Jenis Perangkat: {{ $device }}</p>
                                </div>
                            </div>

                            {{-- User-Agent String Lengkap --}}
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <p class="text-[10px] text-slate-500 font-semibold">User-Agent String (Data Lengkap)</p>
                                    <span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-[9px] font-semibold">data teknis browser</span>
                                </div>
                                <div class="bg-white border border-slate-200 rounded-lg px-3 py-2.5 mb-2">
                                    <p class="text-[10px] font-mono text-slate-600 leading-relaxed break-all">
                                        {{ $log->user_agent ?? '-' }}
                                    </p>
                                </div>


                            </div>
                        </div>
                    </template>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-semibold text-sm">Belum ada catatan log audit</p>
                                <p class="text-gray-400 text-xs">Log akan muncul secara otomatis setelah ada aktivitas di sistem.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL DETAIL LOG AUDIT                                    --}}
    {{-- ======================================================== --}}
    {{-- ======================================================== --}}
    {{-- MODAL DETAIL LOG AUDIT                                    --}}
    {{-- ======================================================== --}}
    {{--
        Struktur:
        #auditDetailModal  → fixed fullscreen, overflow-y-auto  (seluruh layar bisa discroll)
        .modal-backdrop    → absolute fullscreen (klik untuk tutup)
        #auditModalPanel   → relative, lebar max-w-2xl, tinggi otomatis, sticky di tengah via margin
        .modal-body        → overflow-y-auto dengan max-h eksplisit supaya body saja yang scroll
    --}}
    <div id="auditDetailModal"
         class="fixed inset-0 z-50 hidden overflow-y-auto"
         role="dialog" aria-modal="true" aria-labelledby="auditModalTitle">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeAuditModal()"></div>

        {{-- Wrapper tengah agar panel selalu di center --}}
        <div class="flex min-h-full items-center justify-center p-4">

            {{-- Panel --}}
            <div id="auditModalPanel"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col
                        transform transition-all duration-300 scale-95 opacity-0">

                {{-- Header (sticky) --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-purple-700 to-indigo-700 rounded-t-2xl flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="auditModalTitle" class="text-sm font-bold text-white">Detail Log Audit</h3>
                            <p class="text-purple-200 text-[11px]">Informasi lengkap rekam jejak aktivitas</p>
                        </div>
                    </div>
                    <button onclick="closeAuditModal()"
                        class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors"
                        title="Tutup (Esc)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body — scrollable sendiri jika konten sangat panjang --}}
                <div id="auditModalBody" class="overflow-y-auto px-6 py-5" style="max-height: 65vh;">
                    <div class="flex items-center justify-center py-10">
                        <div class="w-6 h-6 border-2 border-purple-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>

                {{-- Footer (sticky bawah) --}}
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between flex-shrink-0 rounded-b-2xl">
                    <p class="text-[10px] text-gray-400 flex items-center gap-1">
                        <svg class="w-3 h-3 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Data bersifat <strong class="text-purple-600 ml-0.5">Append-Only</strong> — tidak dapat diubah atau dihapus
                    </p>
                    <button onclick="closeAuditModal()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript Modal --}}
    <script>
        const auditModal      = document.getElementById('auditDetailModal');
        const auditModalPanel = document.getElementById('auditModalPanel');
        const auditModalBody  = document.getElementById('auditModalBody');

        function openAuditModal(logId) {
            const tpl = document.getElementById('modal-data-' + logId);
            if (!tpl) return;

            // Isi konten
            auditModalBody.innerHTML = tpl.innerHTML;
            // Reset scroll body modal ke atas
            auditModalBody.scrollTop = 0;

            // Tampilkan modal — container discroll (bukan body halaman)
            auditModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animasi masuk
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    auditModalPanel.classList.remove('scale-95', 'opacity-0');
                    auditModalPanel.classList.add('scale-100', 'opacity-100');
                });
            });
        }

        function closeAuditModal() {
            // Animasi keluar
            auditModalPanel.classList.remove('scale-100', 'opacity-100');
            auditModalPanel.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                auditModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 250);
        }

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAuditModal();
        });
    </script>

</x-app-layout>
