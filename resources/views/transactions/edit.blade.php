<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                @if ($isAdminOrSuperadmin)
                    <h2 class="text-xl font-bold text-gray-800">Koreksi Transaksi</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Admin / Superadmin dapat mengoreksi data dan mengubah status alur transaksi</p>
                @else
                    <h2 class="text-xl font-bold text-gray-800">Edit Draft Transaksi</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Perbarui rincian draft transaksi sebelum diajukan ke Checker</p>
                @endif
            </div>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
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
            <div class="bg-gradient-to-r {{ $isAdminOrSuperadmin ? 'from-amber-500 to-amber-600' : 'from-indigo-600 to-indigo-700' }} px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">{{ $isAdminOrSuperadmin ? 'Form Koreksi Transaksi' : 'Form Edit Draft Transaksi' }}</h3>
                        <p class="text-white/70 text-xs mt-0.5">No. Transaksi: {{ $transaction->transaction_number }}</p>
                    </div>
                </div>
                @if ($isAdminOrSuperadmin)
                    <span class="text-xs bg-white/15 border border-white/20 px-2.5 py-1 rounded-full font-semibold">Hak Override Admin</span>
                @endif
            </div>

            <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nomor Transaksi --}}
                <div>
                    <label for="transaction_number" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nomor Transaksi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="transaction_number"
                        id="transaction_number"
                        value="{{ old('transaction_number', $transaction->transaction_number) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500 focus:ring-2 focus:ring-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500/20 text-sm font-mono text-gray-800 transition-all"
                    >
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Deskripsi Transaksi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        value="{{ old('description', $transaction->description) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500 focus:ring-2 focus:ring-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500/20 text-sm text-gray-800 transition-all"
                    >
                </div>

                {{-- Nominal --}}
                <div>
                    <label for="amount" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nominal (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-semibold text-gray-400">Rp</span>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            step="0.01"
                            value="{{ old('amount', $transaction->amount) }}"
                            required
                            class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 focus:border-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500 focus:ring-2 focus:ring-{{ $isAdminOrSuperadmin ? 'amber' : 'indigo' }}-500/20 text-sm font-semibold text-gray-800 transition-all"
                        >
                    </div>
                </div>

                {{-- Override Status (Khusus Admin/Superadmin) --}}
                @if ($isAdminOrSuperadmin)
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">Wewenang Override Status Alur</span>
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                Status Alur Persetujuan <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm text-gray-800 bg-white transition-all">
                                <option value="draft"            {{ old('status', $transaction->status) === 'draft'            ? 'selected' : '' }}>📋 Draft</option>
                                <option value="pending_check"    {{ old('status', $transaction->status) === 'pending_check'    ? 'selected' : '' }}>⏳ Menunggu Checker (pending_check)</option>
                                <option value="pending_approval" {{ old('status', $transaction->status) === 'pending_approval' ? 'selected' : '' }}>🔍 Lolos Checker / Menunggu Approver (pending_approval)</option>
                                <option value="approved"         {{ old('status', $transaction->status) === 'approved'         ? 'selected' : '' }}>✅ Disetujui (approved)</option>
                                <option value="rejected"         {{ old('status', $transaction->status) === 'rejected'         ? 'selected' : '' }}>❌ Ditolak (rejected)</option>
                            </select>
                            <p class="text-[11px] text-amber-700 mt-1.5">Admin & Superadmin berwenang mengubah status alur transaksi secara langsung.</p>
                        </div>

                        <div>
                            <label for="rejection_note" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                Catatan / Alasan Penolakan <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea name="rejection_note" id="rejection_note" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm text-gray-800 bg-white transition-all"
                                placeholder="Isi catatan jika status diubah menjadi Ditolak...">{{ old('rejection_note', $transaction->rejection_note) }}</textarea>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-xl p-3">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-gray-600"><span class="font-bold text-gray-700">Status Transaksi:</span> Draft — Belum diajukan ke Checker.</p>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="pt-4 flex items-center justify-between border-t border-gray-100">
                    {{-- Tombol Hapus --}}
                    <button type="button"
                        onclick="if(confirm('{{ $isAdminOrSuperadmin ? 'Hapus transaksi ' . $transaction->transaction_number . ' sesuai kebijakan sistem?' : 'Hapus draft transaksi ' . $transaction->transaction_number . '?' }}')) document.getElementById('delete-tx-form').submit();"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-xl text-xs font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Transaksi
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 {{ $isAdminOrSuperadmin ? 'bg-amber-500 hover:bg-amber-600' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-tx-form" action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>
