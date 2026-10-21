<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    @if(auth()->user()->hasRole('Inputer') && !auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                        <span>Transaksi Saya</span>
                        <span class="ml-2.5 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">Mode Inputer</span>
                    @else
                        <span>Semua Transaksi</span>
                        <span class="ml-2.5 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 border border-purple-200">Mode Pengawas Sistem (Superadmin & Admin)</span>
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    @if(auth()->user()->hasRole('Inputer') && !auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                        Daftar semua transaksi yang kamu buat dan lacak status alurnya
                    @else
                        Memantau seluruh transaksi dan riwayat approval dalam sistem
                    @endif
                </p>
            </div>
            @can('create transactions')
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-150 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Input Transaksi Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <!-- Flash Success -->
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl shadow-sm" id="flash-success">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div x-data="{
        showModal: false,
        activeTx: null,
        openDetail(tx) {
            this.activeTx = tx;
            this.showModal = true;
        }
    }">

        <!-- Banner Panduan Alur Approval (Biar Pengguna Belajar Mengerti Alurnya) -->
        <div class="mb-6 bg-gradient-to-r from-indigo-50 via-white to-blue-50 border border-indigo-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-800">Alur Persetujuan Transaksi (3 Tahap)</h3>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[11px]">1</span>
                            <div>
                                <p class="font-semibold text-gray-800">Inputer</p>
                                <p class="text-gray-500">Buat & Ajukan transaksi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center justify-center text-[11px]">2</span>
                            <div>
                                <p class="font-semibold text-gray-800">Checker</p>
                                <p class="text-gray-500">Pemeriksaan kelayakan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[11px]">3</span>
                            <div>
                                <p class="font-semibold text-gray-800">Approver</p>
                                <p class="text-gray-500">Keputusan akhir (Setujui/Tolak)</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2.5 text-xs text-indigo-900 bg-indigo-100/60 px-3 py-1.5 rounded-lg inline-block">
                        <strong>📌 Catatan Uji Coba:</strong> Setelah Checker meloloskan transaksi, status menjadi <strong>"Lolos Checker (Menunggu Approver)"</strong>. Untuk menyelesaikan sampai disetujui, login dengan akun <strong>Approver</strong> (<code class="bg-white/80 px-1 py-0.5 rounded font-mono">approver@example.com</code> / sandi: <code class="bg-white/80 px-1 py-0.5 rounded font-mono">BelajarRBAC123!</code>).
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
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Transaksi</th>
                        @if(!auth()->user()->hasRole('Inputer') || auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Inputer</th>
                        @endif
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Alur</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal (WITA)</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($transactions as $transaction)
                        @php
                            $txData = [
                                'id' => $transaction->id,
                                'number' => $transaction->transaction_number,
                                'description' => $transaction->description,
                                'amount' => number_format((float) $transaction->amount, 0, ',', '.'),
                                'status' => $transaction->status,
                                'created_at' => $transaction->created_at->format('d M Y, H:i') . ' WITA',
                                'submitted_at' => $transaction->submitted_at ? $transaction->submitted_at->format('d M Y, H:i') . ' WITA' : null,
                                'checked_at' => $transaction->checked_at ? $transaction->checked_at->format('d M Y, H:i') . ' WITA' : null,
                                'approved_at' => $transaction->approved_at ? $transaction->approved_at->format('d M Y, H:i') . ' WITA' : null,
                                'inputer_name' => $transaction->inputer->name ?? 'Inputer',
                                'checker_name' => $transaction->checker->name ?? null,
                                'approver_name' => $transaction->approver->name ?? null,
                                'rejection_note' => $transaction->rejection_note ?? null,
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-5 py-4 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-semibold bg-gray-100 text-gray-800 px-2 py-1 rounded border border-gray-200">
                                    {{ $transaction->transaction_number }}
                                </span>
                            </td>
                            @if(!auth()->user()->hasRole('Inputer') || auth()->user()->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']))
                            <td class="px-5 py-4">
                                <span class="text-xs font-medium text-indigo-700 bg-indigo-50 px-2 py-1 rounded">
                                    {{ $transaction->inputer->name ?? '-' }}
                                </span>
                            </td>
                            @endif
                            <td class="px-5 py-4 text-gray-700 max-w-xs">
                                <span class="line-clamp-2">{{ $transaction->description }}</span>
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-800 whitespace-nowrap">
                                Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}
                            </td>

                            <!-- Status & Alur -->
                            <td class="px-5 py-4">
                                @if ($transaction->status === 'draft')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Draft
                                        </span>
                                        <span class="text-[11px] text-gray-400">Belum diajukan</span>
                                    </div>

                                @elseif ($transaction->status === 'pending_check')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Checker
                                        </span>
                                        <span class="text-[11px] text-amber-600 font-medium">Sedang dalam antrean Checker</span>
                                    </div>

                                @elseif ($transaction->status === 'pending_approval')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Lolos Checker — Menunggu Approver
                                        </span>
                                        <span class="text-[11px] text-gray-600">
                                            ✓ Diperiksa oleh: <strong class="text-gray-800">{{ $transaction->checker->name ?? 'Checker' }}</strong>
                                        </span>
                                    </div>

                                @elseif ($transaction->status === 'approved')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Disetujui Selesai
                                        </span>
                                        <span class="text-[11px] text-gray-600">
                                            ✓ Oleh: <strong class="text-gray-800">{{ $transaction->approver->name ?? 'Approver' }}</strong>
                                        </span>
                                    </div>

                                @elseif ($transaction->status === 'rejected')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-800 border border-red-200">
                                            <svg class="w-3.5 h-3.5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Ditolak
                                        </span>
                                        @if ($transaction->rejection_note)
                                            <span class="text-[11px] text-red-600 italic line-clamp-1 max-w-[200px]" title="{{ $transaction->rejection_note }}">
                                                "{{ $transaction->rejection_note }}"
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                                {{ $transaction->created_at->format('d M Y, H:i') }} <span class="text-[10px] font-semibold text-gray-400">WITA</span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($transaction->status === 'draft')
                                        <form action="{{ route('transactions.submit', $transaction) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all hover:-translate-y-0.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                                Ajukan
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Detail / Lacak Alur -->
                                    <button
                                        type="button"
                                        @click="openDetail({{ json_encode($txData) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-700 text-gray-700 text-xs font-medium rounded-lg border border-gray-200 transition-colors"
                                        title="Lacak status & riwayat persetujuan">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lacak
                                    </button>

                                    <!-- Tombol Edit (Admin/Superadmin: Semua status, Inputer: Hanya Draft miliknya) -->
                                    @if(auth()->user()->hasAnyRole(['Superadmin', 'Admin']) || (auth()->id() === $transaction->inputer_id && $transaction->status === 'draft'))
                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200 transition-colors"
                                       title="Edit / Koreksi Transaksi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    @endif

                                    <!-- Tombol Hapus (Admin/Superadmin: Semua status, Inputer: Hanya Draft miliknya) -->
                                    @if(auth()->user()->hasAnyRole(['Superadmin', 'Admin']) || (auth()->id() === $transaction->inputer_id && $transaction->status === 'draft'))
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                          onsubmit="return confirm('{{ auth()->user()->hasAnyRole(['Superadmin', 'Admin']) ? "Hapus transaksi " . $transaction->transaction_number . " (Status: " . $transaction->status . ") sesuai kebijakan sistem?" : "Apakah kamu yakin ingin menghapus draft transaksi " . $transaction->transaction_number . "?" }}')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200 transition-colors"
                                            title="Hapus Transaksi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                    @can('create transactions')
                                    <a href="{{ route('transactions.create') }}"
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold underline underline-offset-2">
                                        Buat transaksi pertamamu
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Timeline / Lacak Status Transaksi -->
        <div
            x-show="showModal"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showModal = false"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            ></div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                    class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-10"
                >
                    <!-- Header Modal -->
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base">Detail & Lacak Alur Transaksi</h3>
                            <p class="text-xs text-indigo-200 mt-0.5" x-text="'Nomor: ' + (activeTx ? activeTx.number : '')"></p>
                        </div>
                        <button @click="showModal = false" class="text-indigo-200 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body Modal -->
                    <div class="p-6 space-y-5" x-if="activeTx">
                        <!-- Ringkasan Transaksi -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-400 block mb-0.5">Deskripsi:</span>
                                <span class="font-semibold text-gray-800" x-text="activeTx?.description"></span>
                            </div>
                            <div>
                                <span class="text-gray-400 block mb-0.5">Nominal:</span>
                                <span class="font-bold text-base text-indigo-600" x-text="'Rp ' + (activeTx?.amount || 0)"></span>
                            </div>
                        </div>

                        <!-- Catatan Penolakan (Jika ditolak) -->
                        <div x-show="activeTx?.status === 'rejected'" class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider">Alasan Penolakan:</h4>
                                    <p class="text-sm text-red-700 mt-1" x-text="activeTx?.rejection_note || 'Tidak ada catatan.'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Stepper Timeline -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Riwayat & Progres Alur</h4>
                            <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-gray-200">
                                
                                <!-- Step 1: Inputer -->
                                <div class="relative flex items-start gap-4">
                                    <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-gray-800">1. Pengajuan (Inputer)</p>
                                            <span class="text-[11px] text-gray-400" x-text="activeTx?.submitted_at || activeTx?.created_at"></span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-0.5" x-text="'Dibuat & diajukan oleh: ' + (activeTx?.inputer_name || '-')"></p>
                                    </div>
                                </div>

                                <!-- Step 2: Checker -->
                                <div class="relative flex items-start gap-4">
                                    <template x-if="activeTx?.status === 'draft' || activeTx?.status === 'pending_check'">
                                        <div class="w-7 h-7 rounded-full bg-amber-400 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm animate-pulse">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    </template>
                                    <template x-if="activeTx?.status === 'pending_approval' || activeTx?.status === 'approved'">
                                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </template>
                                    <template x-if="activeTx?.status === 'rejected' && !activeTx?.approver_name">
                                        <div class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </template>

                                    <div class="flex-1 pt-0.5">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-gray-800">2. Pemeriksaan (Checker)</p>
                                            <span class="text-[11px] text-gray-400" x-text="activeTx?.checked_at || '-'"></span>
                                        </div>
                                        <template x-if="activeTx?.status === 'draft'">
                                            <p class="text-xs text-gray-400 mt-0.5">Belum masuk tahap pemeriksaan (transaksi masih draft)</p>
                                        </template>
                                        <template x-if="activeTx?.status === 'pending_check'">
                                            <p class="text-xs text-amber-600 font-medium mt-0.5">Sedang menunggu diperiksa oleh Checker...</p>
                                        </template>
                                        <template x-if="activeTx?.status === 'pending_approval' || activeTx?.status === 'approved' || (activeTx?.status === 'rejected' && activeTx?.approver_name)">
                                            <p class="text-xs text-emerald-700 font-medium mt-0.5" x-text="'✅ Lolos pemeriksaan oleh: ' + (activeTx?.checker_name || 'Checker')"></p>
                                        </template>
                                        <template x-if="activeTx?.status === 'rejected' && !activeTx?.approver_name">
                                            <p class="text-xs text-red-600 font-medium mt-0.5" x-text="'❌ Ditolak oleh Checker (' + (activeTx?.checker_name || '-') + ')'"></p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Step 3: Approver -->
                                <div class="relative flex items-start gap-4">
                                    <template x-if="activeTx?.status === 'draft' || activeTx?.status === 'pending_check'">
                                        <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center flex-shrink-0 z-10">
                                            <span class="text-xs font-bold">3</span>
                                        </div>
                                    </template>
                                    <template x-if="activeTx?.status === 'pending_approval'">
                                        <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm animate-pulse">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    </template>
                                    <template x-if="activeTx?.status === 'approved'">
                                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </template>
                                    <template x-if="activeTx?.status === 'rejected' && activeTx?.approver_name">
                                        <div class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </template>

                                    <div class="flex-1 pt-0.5">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-gray-800">3. Persetujuan Akhir (Approver)</p>
                                            <span class="text-[11px] text-gray-400" x-text="activeTx?.approved_at || '-'"></span>
                                        </div>
                                        <template x-if="activeTx?.status === 'draft' || activeTx?.status === 'pending_check'">
                                            <p class="text-xs text-gray-400 mt-0.5">Menunggu tahap pemeriksaan Checker selesai</p>
                                        </template>
                                        <template x-if="activeTx?.status === 'pending_approval'">
                                            <p class="text-xs text-blue-700 font-semibold mt-0.5">
                                                ⏳ Menunggu persetujuan Approver (Login sebagai Approver untuk menyetujui)
                                            </p>
                                        </template>
                                        <template x-if="activeTx?.status === 'approved'">
                                            <p class="text-xs text-emerald-700 font-semibold mt-0.5" x-text="'🎉 Disetujui sepenuhnya oleh: ' + (activeTx?.approver_name || 'Approver')"></p>
                                        </template>
                                        <template x-if="activeTx?.status === 'rejected' && activeTx?.approver_name">
                                            <p class="text-xs text-red-600 font-semibold mt-0.5" x-text="'❌ Ditolak oleh Approver (' + (activeTx?.approver_name || '-') + ')'"></p>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-xl transition-colors"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
