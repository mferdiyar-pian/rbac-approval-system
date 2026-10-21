<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Persetujuan Transaksi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Transaksi yang telah lolos Checker dan menunggu keputusan akhir dari Approver</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold rounded-full">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Menunggu Persetujuan Akhir
            </span>
        </div>
    </x-slot>

    {{-- Flash Success --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl shadow-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
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
    @endif

    {{-- Daftar Transaksi --}}
    <div class="space-y-4">
        @forelse ($transactions as $transaction)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- Header Kartu --}}
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-100 flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-200 text-blue-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                            Menunggu Persetujuan
                        </span>
                        <span class="font-mono text-sm text-gray-700 font-bold">{{ $transaction->transaction_number }}</span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Inputer: <strong class="text-gray-700 ml-1">{{ $transaction->inputer->name ?? '-' }}</strong>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/>
                            </svg>
                            Checker: <strong class="text-gray-700 ml-1">{{ $transaction->checker->name ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Detail Transaksi --}}
                <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-4 gap-4 border-b border-gray-100">
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Deskripsi</p>
                        <p class="text-gray-800 font-medium text-sm">{{ $transaction->description }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Nominal</p>
                        <p class="text-gray-900 font-bold text-xl">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Diperiksa Pada</p>
                        <p class="text-gray-600 text-sm font-medium">
                            {{ $transaction->checked_at ? $transaction->checked_at->format('d M Y, H:i') . ' WITA' : '-' }}
                        </p>
                    </div>
                </div>

                {{-- Area Aksi --}}
                <div class="px-6 py-4 bg-gray-50/50 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    {{-- Tombol Setujui --}}
                    <form action="{{ route('transactions.approve', $transaction) }}" method="POST"
                          onsubmit="return confirm('Anda yakin ingin MENYETUJUI transaksi {{ $transaction->transaction_number }}? Keputusan ini bersifat final.')">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Setujui Transaksi
                        </button>
                    </form>

                    <span class="hidden sm:block text-gray-300 text-sm">|</span>

                    {{-- Form Tolak --}}
                    <form action="{{ route('transactions.reject-approver', $transaction) }}" method="POST"
                          class="flex-1 flex flex-col sm:flex-row gap-2">
                        @csrf
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <input
                                type="text"
                                name="rejection_note"
                                placeholder="Tulis alasan penolakan dengan jelas..."
                                required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:border-red-400 focus:ring-2 focus:ring-red-400/20 outline-none text-sm text-gray-800 placeholder:text-gray-400 transition-all"
                            >
                        </div>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-base">Tidak ada transaksi yang menunggu persetujuan</p>
                        <p class="text-gray-500 text-sm mt-1">Semua transaksi sudah ditangani.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
