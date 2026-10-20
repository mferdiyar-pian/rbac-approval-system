<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Log Audit Sistem (Audit Trail)</h2>
                <p class="text-sm text-gray-500 mt-0.5">Rekam jejak otomatis seluruh aktivitas dan perubahan data dalam sistem</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-full border border-purple-200">
                <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                Zona Waktu: WITA (UTC+8)
            </span>
        </div>
    </x-slot>

    <!-- Info Banner Edukasi Log Audit -->
    <div class="mb-6 bg-gradient-to-r from-purple-50 via-white to-indigo-50 border border-purple-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-gray-800">Apa Itu Log Audit & Mengapa Dicatat?</h3>
                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                    Log audit adalah <strong>CCTV digital</strong> yang mencatat setiap peristiwa penting: <strong>Siapa</strong> yang melakukannya, <strong>Kapan</strong> waktunya (WITA), <strong>Apa</strong> yang diubah, dan dari <strong>IP Address</strong> mana. Log ini dilindungi fitur <em>Append-Only</em> sehingga tidak dapat dihapus atau diubah guna menjaga transparansi dan integritas audit.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mb-5 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <!-- Filter Aksi -->
                <select
                    name="action"
                    onchange="this.form.submit()"
                    class="px-3 py-2 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-700 bg-white"
                >
                    <option value="">-- Semua Kategori Aksi --</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                            {{ $act }}
                        </option>
                    @endforeach
                </select>

                <!-- Input Pencarian -->
                <div class="relative flex-1 sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari deskripsi, user, atau IP..."
                        class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-800 placeholder:text-gray-400"
                    >
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                    Filter
                </button>
                @if (request()->hasAny(['action', 'search']))
                    <a href="{{ route('audit-logs.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Kejadian (WITA)</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Aktivitas</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP & Perangkat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    @php
                        $actionBadge = match(true) {
                            $log->action === 'LOGIN'              => ['bg' => 'bg-cyan-50 text-cyan-800 border-cyan-200', 'dot' => 'bg-cyan-500'],
                            $log->action === 'LOGOUT'             => ['bg' => 'bg-slate-100 text-slate-700 border-slate-300', 'dot' => 'bg-slate-400'],
                            str_contains($log->action, 'APPROVE') => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
                            str_contains($log->action, 'CHECK')   => ['bg' => 'bg-teal-50 text-teal-800 border-teal-200', 'dot' => 'bg-teal-500'],
                            str_contains($log->action, 'CREATE')  => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'dot' => 'bg-blue-500'],
                            str_contains($log->action, 'SUBMIT')  => ['bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200', 'dot' => 'bg-indigo-500'],
                            str_contains($log->action, 'EDIT')    => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500'],
                            str_contains($log->action, 'REJECT') || str_contains($log->action, 'DELETE') => ['bg' => 'bg-red-50 text-red-800 border-red-200', 'dot' => 'bg-red-500'],
                            default                               => ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'dot' => 'bg-gray-400'],
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <!-- Waktu -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-gray-800 block">
                                {{ $log->created_at->format('d M Y') }}
                            </span>
                            <span class="text-[11px] text-gray-500 font-mono">
                                {{ $log->created_at->format('H:i:s') }} WITA <span class="text-gray-400">({{ $log->created_at->diffForHumans() }})</span>
                            </span>
                        </td>

                        <!-- Pengguna -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">{{ $log->user_name }}</p>
                                    <span class="text-[10px] text-gray-500 font-medium">Role: {{ $log->user_role }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $actionBadge['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $actionBadge['dot'] }}"></span>
                                {{ $log->action }}
                            </span>
                        </td>

                        <!-- Deskripsi Aktivitas -->
                        <td class="px-5 py-4 text-xs text-gray-700 max-w-md">
                            <p class="leading-relaxed">{{ $log->description }}</p>
                        </td>

                        <!-- IP & Perangkat -->
                        <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500">
                            <span class="font-mono text-[11px] bg-gray-100 text-gray-700 px-2 py-0.5 rounded block w-fit mb-0.5">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </span>
                            <span class="text-[10px] text-gray-400 truncate max-w-[140px] block" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 20) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada catatan log audit</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($logs->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
