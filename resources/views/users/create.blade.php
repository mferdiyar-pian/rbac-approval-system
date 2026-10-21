<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tambah Pengguna Baru</h2>
                <p class="text-sm text-gray-500 mt-0.5">Daftarkan akun pengguna baru dan tentukan hak akses rolenya</p>
            </div>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Periksa kembali data yang kamu masukkan:</p>
                    <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center gap-3 text-white">
                <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Form Pendaftaran Akun Baru</h3>
                    <p class="text-indigo-200 text-xs mt-0.5">Isi semua field yang bertanda bintang (*)</p>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text" name="name" id="name"
                        value="{{ old('name') }}" required
                        placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm text-gray-800 placeholder:text-gray-400 transition-all"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email" name="email" id="email"
                        value="{{ old('email') }}" required
                        placeholder="contoh@perusahaan.com"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm text-gray-800 placeholder:text-gray-400 transition-all"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password" name="password" id="password"
                        required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm text-gray-800 placeholder:text-gray-400 transition-all"
                    >
                    <p class="text-xs text-gray-400 mt-1.5">Gunakan kombinasi huruf besar, angka, dan simbol untuk keamanan maksimal.</p>
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Pilih Role Akun <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm text-gray-800 transition-all">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Panduan Role --}}
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @php
                            $roleGuide = [
                                ['icon' => '👑', 'name' => 'Superadmin', 'desc' => 'Akses penuh semua modul & akun', 'color' => 'purple'],
                                ['icon' => '🔧', 'name' => 'Admin', 'desc' => 'Kelola pengguna & transaksi', 'color' => 'blue'],
                                ['icon' => '📝', 'name' => 'Inputer', 'desc' => 'Membuat & mengajukan transaksi', 'color' => 'indigo'],
                                ['icon' => '🔍', 'name' => 'Checker', 'desc' => 'Memeriksa & meloloskan transaksi', 'color' => 'amber'],
                                ['icon' => '✅', 'name' => 'Approver', 'desc' => 'Menyetujui / menolak final', 'color' => 'emerald'],
                                ['icon' => '👤', 'name' => 'User', 'desc' => 'Akses membaca biasa', 'color' => 'gray'],
                            ];
                        @endphp
                        @foreach ($roleGuide as $guide)
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 text-xs text-gray-600">
                                <span>{{ $guide['icon'] }}</span>
                                <span><strong class="text-gray-800">{{ $guide['name'] }}:</strong> {{ $guide['desc'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('users.index') }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
