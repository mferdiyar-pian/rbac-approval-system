<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi (Semua transaksi untuk Superadmin/Admin, milik sendiri untuk Inputer)
    // Menggunakan eager loading with(['checker', 'approver', 'inputer']) untuk memuat relasi pengguna alur secara efisien dan mencegah N+1 query.
    public function index()
    {
        $user = Auth::user();
        $query = Transaction::with(['checker', 'approver', 'inputer'])->latest();

        // Jika bukan Superadmin, Admin, Checker, atau Approver, batasi ke transaksi miliknya sendiri
        if (!$user->hasAnyRole(['Superadmin', 'Admin', 'Checker', 'Approver']) && !$user->can('edit transactions')) {
            $query->where('inputer_id', $user->id);
        }

        $transactions = $query->get();

        return view('transactions.index', compact('transactions'));
    }

    // Menampilkan form input transaksi
    public function create()
    {
        return view('transactions.create');
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_number' => [
                'required',
                'string',
                'max:255',
                'unique:transactions,transaction_number',
            ],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $transaction = Transaction::create([
            'transaction_number' => $validated['transaction_number'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'status' => 'draft',
            'inputer_id' => Auth::id(),
        ]);

        AuditLog::record('CREATE_TRANSACTION', "Membuat draft transaksi {$transaction->transaction_number} senilai Rp " . number_format((float) $transaction->amount, 0, ',', '.'));

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan sebagai draft.');

            
    }


    // Mengajukan transaksi untuk diperiksa Checker
    public function submit(Transaction $transaction)
    {
        // Pastikan transaksi milik Inputer yang sedang login
        abort_unless(
            $transaction->inputer_id === auth()->id(),
            403,
            'Kamu tidak berhak mengajukan transaksi ini.'
        );

        // Hanya draft yang boleh diajukan
        abort_unless(
            $transaction->status === 'draft',
            403,
            'Hanya transaksi berstatus draft yang dapat diajukan.'
        );

        $transaction->update([
            'status' => 'pending_check',
            'submitted_at' => now(),
        ]);

        AuditLog::record('SUBMIT_TRANSACTION', "Mengajukan transaksi {$transaction->transaction_number} ke Checker untuk diperiksa");

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diajukan kepada Checker.');
    }

    
    public function checkerIndex()
    {
        $transactions = Transaction::with('inputer')
            ->where('status', 'pending_check')
            ->latest()
            ->get();

        return view('transactions.checker', compact('transactions'));
    }

    // Checker meloloskan transaksi
    public function check(Transaction $transaction)
    {
        abort_unless(
            $transaction->status === 'pending_check',
            403,
            'Hanya transaksi berstatus pending_check yang dapat diperiksa.'
        );

        // Prinsip Segregation of Duties (SoD): Pembuat transaksi dilarang memeriksa transaksinya sendiri
        abort_if(
            $transaction->inputer_id === auth()->id(),
            403,
            'Prinsip Segregation of Duties: Inputer tidak dapat memeriksa transaksi yang dibuat sendiri.'
        );

        $transaction->update([
            'status'     => 'pending_approval',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        AuditLog::record('CHECK_TRANSACTION', "Meloloskan pemeriksaan transaksi {$transaction->transaction_number} dan meneruskan ke Approver");

        return redirect()
            ->route('transactions.checker')
            ->with('success', 'Transaksi berhasil diloloskan pemeriksaan.');
    }

    // Checker menolak transaksi
    public function rejectByChecker(Request $request, Transaction $transaction)
    {
        $request->validate([
            'rejection_note' => ['required', 'string', 'max:500'],
        ]);

        abort_unless(
            $transaction->status === 'pending_check',
            403,
            'Hanya transaksi berstatus pending_check yang dapat ditolak.'
        );

        // Prinsip Segregation of Duties (SoD): Pembuat transaksi dilarang memeriksa transaksinya sendiri
        abort_if(
            $transaction->inputer_id === auth()->id(),
            403,
            'Prinsip Segregation of Duties: Inputer tidak dapat memeriksa transaksi yang dibuat sendiri.'
        );

        $transaction->update([
            'status'         => 'rejected',
            'rejection_note' => $request->rejection_note,
        ]);

        AuditLog::record('REJECT_BY_CHECKER', "Menolak transaksi {$transaction->transaction_number} pada tahap pemeriksaan. Alasan: {$request->rejection_note}");

        return redirect()
            ->route('transactions.checker')
            ->with('success', 'Transaksi berhasil ditolak.');
    }

    
    // Menampilkan transaksi yang menunggu persetujuan
    public function approverIndex()
    {
        $transactions = Transaction::with(['inputer', 'checker'])
            ->where('status', 'pending_approval')
            ->latest()
            ->get();

        return view('transactions.approver', compact('transactions'));
    }

    // Approver menyetujui transaksi
    public function approve(Transaction $transaction)
    {
        abort_unless(
            auth()->user()->can('approve transactions'),
            403,
            'Kamu tidak memiliki izin menyetujui transaksi.'
        );

        abort_unless(
            $transaction->status === 'pending_approval',
            403,
            'Hanya transaksi yang menunggu persetujuan yang dapat disetujui.'
        );

        // Prinsip Segregation of Duties (SoD): Pembuat transaksi atau Checker dilarang menyetujui transaksi
        abort_if(
            $transaction->inputer_id === auth()->id(),
            403,
            'Prinsip Segregation of Duties: Inputer tidak dapat menyetujui transaksi yang dibuat sendiri.'
        );

        abort_if(
            $transaction->checker_id === auth()->id(),
            403,
            'Prinsip Segregation of Duties: Pemeriksa (Checker) tidak dapat memberikan persetujuan akhir pada transaksi yang sama.'
        );

        $transaction->update([
            'status' => 'approved',
            'approver_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        AuditLog::record('APPROVE_TRANSACTION', "Memberikan persetujuan akhir (Approved) untuk transaksi {$transaction->transaction_number}");

        return redirect()
            ->route('transactions.approver')
            ->with('success', 'Transaksi berhasil disetujui.');
    }

    // Approver menolak transaksi
    public function rejectByApprover(
        Request $request,
        Transaction $transaction
    ) {
        abort_unless(
            auth()->user()->can('approve transactions'),
            403,
            'Kamu tidak memiliki izin menolak transaksi.'
        );

        abort_unless(
            $transaction->status === 'pending_approval',
            403,
            'Hanya transaksi yang menunggu persetujuan yang dapat ditolak.'
        );

        // Prinsip Segregation of Duties (SoD): Pembuat transaksi dilarang bertindak sebagai Approver pada transaksinya sendiri
        abort_if(
            $transaction->inputer_id === auth()->id(),
            403,
            'Prinsip Segregation of Duties: Inputer tidak dapat memproses persetujuan transaksi yang dibuat sendiri.'
        );

        $validated = $request->validate([
            'rejection_note' => ['required', 'string', 'max:1000'],
        ]);

        $transaction->update([
            'status' => 'rejected',
            'approver_id' => auth()->id(),
            'rejection_note' => $validated['rejection_note'],
        ]);

        AuditLog::record('REJECT_BY_APPROVER', "Menolak transaksi {$transaction->transaction_number} pada tahap persetujuan final. Alasan: {$validated['rejection_note']}");

        return redirect()
            ->route('transactions.approver')
            ->with('success', 'Transaksi berhasil ditolak.');
    }

    // Edit Transaksi:
    // - Admin & Superadmin: Bisa mengedit transaksi siapapun dalam status apapun (koreksi data / override alur).
    // - Inputer: Hanya bisa mengedit draft miliknya sendiri sebelum diajukan.
    public function edit(Transaction $transaction)
    {
        $user = auth()->user();
        $isAdminOrSuperadmin = $user->hasAnyRole(['Superadmin', 'Admin']) || $user->can('manage users');

        if (!$isAdminOrSuperadmin) {
            abort_unless(
                $transaction->inputer_id === $user->id && $transaction->status === 'draft',
                403,
                'Inputer hanya dapat mengedit transaksi miliknya sendiri yang masih berstatus draft.'
            );
        }

        return view('transactions.edit', compact('transaction', 'isAdminOrSuperadmin'));
    }

    // Simpan Perubahan Transaksi
    public function update(Request $request, Transaction $transaction)
    {
        $user = auth()->user();
        $isAdminOrSuperadmin = $user->hasAnyRole(['Superadmin', 'Admin']) || $user->can('manage users');

        if (!$isAdminOrSuperadmin) {
            abort_unless(
                $transaction->inputer_id === $user->id && $transaction->status === 'draft',
                403,
                'Inputer hanya dapat mengedit transaksi miliknya sendiri yang masih berstatus draft.'
            );
        }

        $rules = [
            'transaction_number' => [
                'required',
                'string',
                'max:255',
                'unique:transactions,transaction_number,' . $transaction->id,
            ],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];

        // Hanya Admin/Superadmin yang bisa mengganti status alur secara manual (override status alur)
        if ($isAdminOrSuperadmin) {
            $rules['status'] = ['required', 'in:draft,pending_check,pending_approval,approved,rejected'];
            $rules['rejection_note'] = ['nullable', 'string', 'max:1000'];
        }

        $validated = $request->validate($rules);
        $transaction->update($validated);

        if ($isAdminOrSuperadmin) {
            AuditLog::record('CORRECT_TRANSACTION', "Admin/Superadmin mengoreksi transaksi {$transaction->transaction_number} (Nominal: Rp " . number_format((float) $transaction->amount, 0, ',', '.') . ", Status: {$transaction->status})");
        } else {
            AuditLog::record('EDIT_DRAFT_TRANSACTION', "Inputer memperbarui draft transaksi {$transaction->transaction_number} (Nominal: Rp " . number_format((float) $transaction->amount, 0, ',', '.') . ")");
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi ' . $transaction->transaction_number . ' berhasil diperbarui.');
    }

    // Hapus Transaksi:
    // - Admin & Superadmin: Bisa menghapus transaksi apapun.
    // - Inputer: Bisa menghapus transaksi miliknya jika masih berstatus draft.
    public function destroy(Transaction $transaction)
    {
        $user = auth()->user();
        $isAdminOrSuperadmin = $user->hasAnyRole(['Superadmin', 'Admin']) || $user->can('manage users') || $user->can('delete transactions');

        if (!$isAdminOrSuperadmin) {
            abort_unless(
                $transaction->inputer_id === $user->id && $transaction->status === 'draft',
                403,
                'Hanya transaksi berstatus draft atau melalui akun Admin/Superadmin yang dapat dihapus.'
            );
        }

        $number = $transaction->transaction_number;
        $transaction->delete();

        AuditLog::record('DELETE_TRANSACTION', "Menghapus transaksi {$number}");

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi ' . $number . ' berhasil dihapus.');
    }

}
