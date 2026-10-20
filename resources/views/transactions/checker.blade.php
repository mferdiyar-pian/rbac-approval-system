<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Pemeriksaan Transaksi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Transaksi yang menunggu diperiksa oleh Checker</p>
            </div>
        </div>
    </x-slot>

    <!-- Flash / Errors -->
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

    <!-- Table or Cards for each transaction -->
    @forelse ($transactions as $transaction)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5 overflow-hidden">
            <!-- Transaction Info Header -->
            <div class="px-6 py-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-200 text-amber-800">
                        Menunggu Pemeriksaan
                    </span>
                    <span class="font-mono text-sm text-gray-700 font-semibold">{{ $transaction->transaction_number }}</span>
                </div>
                <span class="text-xs text-gray-500">Inputer: <strong>{{ $transaction->inputer->name ?? '-' }}</strong></span>
            </div>

            <!-- Transaction Details -->
            <div class="px-6 py-4 grid grid-cols-2 md:grid-cols-3 gap-4 border-b border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Deskripsi</p>
                    <p class="text-gray-800 font-medium">{{ $transaction->description }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Nominal</p>
                    <p class="text-gray-800 font-bold text-lg">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Diajukan Pada (WITA)</p>
                    <p class="text-gray-600 text-sm font-medium">{{ ($transaction->submitted_at ?? $transaction->created_at)->format('d M Y, H:i') }} WITA</p>
                </div>
            </div>

            <!-- Action Area -->
            <div class="px-6 py-4 flex flex-col sm:flex-row gap-4">
                <!-- Approve -->
                <form action="{{ route('transactions.check', $transaction) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Lolos Pemeriksaan
                    </button>
                </form>

                <!-- Reject -->
                <form action="{{ route('transactions.reject-checker', $transaction) }}" method="POST"
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
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak
                    </button>
                </form>
            </div>
        </div>

    @empty
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/>
                </svg>
                <p class="text-gray-600 font-semibold">Tidak ada transaksi yang menunggu pemeriksaan</p>
                <p class="text-gray-400 text-sm">Semua transaksi sudah ditangani.</p>
            </div>
        </div>
    @endforelse
</x-app-layout>
