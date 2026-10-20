<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}"
               class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Input Transaksi Baru</h2>
                <p class="text-sm text-gray-500 mt-0.5">Isi form di bawah untuk membuat transaksi baru sebagai draft</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Terdapat kesalahan:</p>
                        <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nomor Transaksi -->
                <div>
                    <label for="transaction_number" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nomor Transaksi
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input
                        type="text"
                        id="transaction_number"
                        name="transaction_number"
                        value="{{ old('transaction_number') }}"
                        required
                        placeholder="Contoh: TRX-20241020-001"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-gray-800 text-sm transition-all placeholder:text-gray-400
                               @error('transaction_number') border-red-400 bg-red-50 @enderror"
                    >
                    @error('transaction_number')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Deskripsi Transaksi
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input
                        type="text"
                        id="description"
                        name="description"
                        value="{{ old('description') }}"
                        required
                        placeholder="Jelaskan tujuan transaksi ini"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-gray-800 text-sm transition-all placeholder:text-gray-400
                               @error('description') border-red-400 bg-red-50 @enderror"
                    >
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nominal -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nominal (Rp)
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-medium">Rp</span>
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            min="0.01"
                            step="0.01"
                            value="{{ old('amount') }}"
                            required
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-gray-800 text-sm transition-all placeholder:text-gray-400
                                   @error('amount') border-red-400 bg-red-50 @enderror"
                        >
                    </div>
                    @error('amount')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan sebagai Draft
                    </button>
                    <a href="{{ route('transactions.index') }}"
                       class="px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Note -->
        <div class="mt-4 flex items-start gap-2 text-xs text-gray-500">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p>Transaksi akan disimpan sebagai <strong>Draft</strong>. Kamu bisa mengajukannya ke Checker dari halaman Daftar Transaksi.</p>
        </div>
    </div>
</x-app-layout>
