<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Pengguna & Akun</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola akun, hak akses role, dan permissions pengguna sistem</p>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-150 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Pengguna Baru
            </a>
        </div>
    </x-slot>

    <!-- Flash Success -->
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Flash Error -->
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                👥
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Akun</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                👑
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Superadmin & Admin</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ $users->filter(fn($u) => $u->hasAnyRole(['Superadmin', 'Admin']))->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                🔍
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Checker & Approver</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ $users->filter(fn($u) => $u->hasAnyRole(['Checker', 'Approver']))->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                📝
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Inputer (Maker)</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ $users->filter(fn($u) => $u->hasRole('Inputer'))->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No.</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role Utama</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar (WITA)</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $u)
                    <tr class="hover:bg-gray-50/80 transition-colors {{ $u->id === auth()->id() ? 'bg-indigo-50/30' : '' }}">
                        <td class="px-5 py-4 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-bold text-gray-800">{{ $u->name }}</p>
                                        @if ($u->id === auth()->id())
                                            <span class="text-[10px] bg-indigo-600 text-white px-1.5 py-0.5 rounded-full font-semibold">Akun Kamu</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 font-mono">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @forelse ($u->roles as $role)
                                @php
                                    $roleColor = match($role->name) {
                                        'Superadmin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'Admin'      => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Checker'    => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'Approver'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'Inputer'    => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        default      => 'bg-gray-100 text-gray-800 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $roleColor }}">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic">Tanpa Role</span>
                            @endforelse
                        </td>
                        <td class="px-5 py-4 text-gray-700 font-medium">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-gray-100 rounded-md text-xs font-semibold text-gray-700">
                                {{ $u->transactions_count }} dibuat
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ $u->created_at->format('d M Y, H:i') }} <span class="text-[10px] font-semibold text-gray-400">WITA</span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $u) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200 transition-colors"
                                   title="Edit Data & Role Pengguna">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $u) }}" method="POST"
                                          onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun {{ $u->name }} ({{ $u->email }})?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200 transition-colors"
                                            title="Hapus Pengguna">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-300 select-none px-2 py-1">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
