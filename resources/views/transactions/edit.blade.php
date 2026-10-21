<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    @if ($isAdminOrSuperadmin)
                        Koreksi Transaksi (Admin / Superadmin)
                    @else
                        Edit Draft Transaksi
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    @if ($isAdminOrSuperadmin)
                        Koreksi data transaksi atau lakukan penyesuaian alur sesuai kewenangan sistem
                    @else
                        Perbarui rincian draft transaksi milikmu sebelum diajukan ke tahap pemeriksaan
                    @endif
                </p>
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
        <!-- Error Alerts -->
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
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
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r {{ $isAdminOrSuperadmin ? 'from-amber-600 to-amber-700' : 'from-indigo-600 to-indigo-700' }} px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <h3 class="font-bold text-sm uppercase tracking-wider">
                        {{ $isAdminOrSuperadmin ? 'Form Koreksi Transaksi' : 'Form Edit Draft Transaksi' }}
                    </h3>
                </div>
                <span class="text-xs {{ $isAdminOrSuperadmin ? 'bg-amber-800/60' : 'bg-indigo-800/60' }} px-2.5 py-1 rounded-full font-mono">{{ $transaction->transaction_number }}</span>
            </div>

            <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nomor Transaksi -->
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
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-mono text-gray-800 transition-all"
                    >
                </div>

                <!-- Deskripsi -->
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
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm text-gray-800 transition-all"
                    >
                </div>

                <!-- Nominal -->
                <div>
                    <label for="amount" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nominal (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-semibold text-gray-400">Rp</span>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            step="0.01"
                            value="{{ old('amount', $transaction->amount) }}"
                            required
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-semibold text-gray-800 transition-all"
                        >
                    </div>
                </div>

                @if ($isAdminOrSuperadmin)
                <!-- Box Wewenang Override Status Alur (Khusus Admin / Superadmin) -->
                <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-200 text-amber-900">
                            Wewenang Override
                        </span>
                        <span class="text-xs font-bold text-amber-900">Penyesuaian Status Alur Transaksi</span>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Status Alur Persetujuan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status"
                            id="status"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm text-gray-800 transition-all bg-white"
                        >
                            <option value="draft" {{ old('status', $transaction->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_check" {{ old('status', $transaction->status) === 'pending_check' ? 'selected' : '' }}>Menunggu Checker (pending_check)</option>
                            <option value="pending_approval" {{ old('status', $transaction->status) === 'pending_approval' ? 'selected' : '' }}>Lolos Checker / Menunggu Approver (pending_approval)</option>
                            <option value="approved" {{ old('status', $transaction->status) === 'approved' ? 'selected' : '' }}>Disetujui Selesai (approved)</option>
                            <option value="rejected" {{ old('status', $transaction->status) === 'rejected' ? 'selected' : '' }}>Ditolak (rejected)</option>
                        </select>
                        <p class="text-[11px] text-amber-800/80 mt-1">Superadmin & Admin memiliki wewenang override mengubah status alur transaksi secara langsung.</p>
                    </div>

                    <!-- Catatan Penolakan (Opsional / Jika Ditolak) -->
                    <div>
                        <label for="rejection_note" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Catatan / Alasan Penolakan (Opsional)
                        </label>
                        <textarea
                            name="rejection_note"
                            id="rejection_note"
                            rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm text-gray-800 transition-all bg-white"
                            placeholder="Isi catatan jika status diubah menjadi ditolak..."
                        >{{ old('rejection_note', $transaction->rejection_note) }}</textarea>
                    </div>
                </div>
                @else
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600">
                    <span class="font-bold text-gray-800">Status Transaksi:</span> Draft (Belum diajukan ke Checker).
                </div>
                @endif

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('transactions.index') }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 {{ $isAdminOrSuperadmin ? 'bg-amber-600 hover:bg-amber-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
