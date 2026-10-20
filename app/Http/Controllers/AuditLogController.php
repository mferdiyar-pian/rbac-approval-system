<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Menampilkan riwayat log audit aktivitas sistem.
     */
    public function index(Request $request)
    {
        abort_unless(
            auth()->user()->can('view audit logs'),
            403,
            'Kamu tidak memiliki izin melihat Log Audit.'
        );

        $query = AuditLog::with('user')->latest();

        // Filter berdasarkan aksi jika dipilih
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Pencarian teks deskripsi atau nama pengguna
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->withQueryString();

        // Daftar kategori aksi unik untuk dropdown filter
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('audit-logs.index', compact('logs', 'actions'));
    }
}
