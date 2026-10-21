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
                {{-- Filter Kategori Aksi --}}
                <select name="action" onchange="this.form.submit()"
                    class="px-3 py-2.5 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-700 bg-white min-w-[200px]">
                    <option value="">— Semua Kategori Aksi —</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                            {{ $act }}
                        </option>
                    @endforeach
                </select>

                {{-- Input Pencarian --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan deskripsi, nama user, atau IP..."
                        class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-gray-800 placeholder:text-gray-400"
                    >
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
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    @php
                        $actionBadge = match(true) {
                            str_contains($log->action, 'APPROVE')                               => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
                            str_contains($log->action, 'CHECK')                                 => ['bg' => 'bg-teal-50 text-teal-800 border-teal-200',     'dot' => 'bg-teal-500'],
                            str_contains($log->action, 'CREATE')                                => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200',      'dot' => 'bg-blue-500'],
                            str_contains($log->action, 'SUBMIT')                                => ['bg' => 'bg-indigo-50 text-indigo-800 border-indigo-200','dot' => 'bg-indigo-500'],
                            str_contains($log->action, 'EDIT') || str_contains($log->action, 'CORRECT') || str_contains($log->action, 'OVERRIDE') => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500'],
                            str_contains($log->action, 'REJECT') || str_contains($log->action, 'DELETE') => ['bg' => 'bg-red-50 text-red-800 border-red-200', 'dot' => 'bg-red-500'],
                            default                                                              => ['bg' => 'bg-gray-100 text-gray-700 border-gray-200',    'dot' => 'bg-gray-400'],
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
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
</x-app-layout>
