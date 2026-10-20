<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Persetujuan Transaksi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Transaksi yang sudah lolos Checker dan menunggu persetujuan akhir</p>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

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

    @forelse ($transactions as $transaction)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-100 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-200 text-blue-800">
                        Menunggu Persetujuan
                    </span>
                    <span class="font-mono text-sm text-gray-700 font-semibold">{{ $transaction->transaction_number }}</span>
                </div>
                <div class="text-xs text-gray-500 space-y-0.5 text-right">
                    <p>Inputer: <strong>{{ $transaction->inputer->name ?? '-' }}</strong></p>
                    <p>Checker: <strong>{{ $transaction->checker->name ?? '-' }}</strong></p>
                </div>
            </div>

            <!-- Detail -->
            <div class="px-6 py-4 grid grid-cols-2 gap-x-8 gap-y-4 border-b border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Deskripsi</p>
                    <p class="text-gray-800 font-medium">{{ $transaction->description }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Nominal</p>
                    <p class="text-gray-800 font-bold text-xl">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Tanggal Diajukan (WITA)</p>
                    <p class="text-gray-600 text-sm font-medium">{{ $transaction->submitted_at ? $transaction->submitted_at->format('d M Y, H:i') . ' WITA' : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Tanggal Diperiksa (WITA)</p>
                    <p class="text-gray-600 text-sm font-medium">{{ $transaction->checked_at ? $transaction->checked_at->format('d M Y, H:i') . ' WITA' : '-' }}</p>
                </div>
            </div>

            <!-- Aksi -->
            <div class="px-6 py-4 bg-gray-50/50 flex flex-col sm:flex-row gap-4">
                <!-- Setujui -->
                <form action="{{ route('transactions.approve', $transaction) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Setujui Transaksi
                    </button>
                </form>

                <!-- Tolak -->
                <form action="{{ route('transactions.reject-approver', $transaction) }}" method="POST"
                      class="flex-1 flex flex-col sm:flex-row gap-2">
                    @csrf
                    <input
                        type="text"
                        name="rejection_note"
                        placeholder="Tulis alasan penolakan..."
                        required
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 focus:border-red-400 focus:ring-2 focus:ring-red-400/20 outline-none text-sm text-gray-800 placeholder:text-gray-400 transition-all"
                    >
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tolak
                    </button>
                </form>
            </div>
        </div>

    @empty
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-gray-600 font-semibold">Tidak ada transaksi yang menunggu persetujuan</p>
                <p class="text-gray-400 text-sm">Semua transaksi sudah ditangani.</p>
            </div>
        </div>
    @endforelse
</x-app-layout>
