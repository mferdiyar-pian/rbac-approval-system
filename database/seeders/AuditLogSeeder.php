<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            [
                'user_name' => 'Inputer',
                'user_role' => 'Inputer',
                'action' => 'CREATE_TRANSACTION',
                'description' => 'Membuat draft transaksi TRX-001 senilai Rp 150.000',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(8),
            ],
            [
                'user_name' => 'Inputer',
                'user_role' => 'Inputer',
                'action' => 'SUBMIT_TRANSACTION',
                'description' => 'Mengajukan transaksi TRX-001 ke Checker untuk diperiksa',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(7),
            ],
            [
                'user_name' => 'Checker',
                'user_role' => 'Checker',
                'action' => 'CHECK_TRANSACTION',
                'description' => 'Meloloskan pemeriksaan transaksi TRX-001 dan meneruskan ke Approver',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(6),
            ],
            [
                'user_name' => 'Inputer',
                'user_role' => 'Inputer',
                'action' => 'CREATE_TRANSACTION',
                'description' => 'Membuat draft transaksi TRX-002 senilai Rp 20',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(5),
            ],
            [
                'user_name' => 'Checker',
                'user_role' => 'Checker',
                'action' => 'REJECT_BY_CHECKER',
                'description' => 'Menolak transaksi TRX-002 pada tahap pemeriksaan. Alasan: Nominal tidak wajar/keliru',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(4),
            ],
            [
                'user_name' => 'Superadmin',
                'user_role' => 'Superadmin',
                'action' => 'CREATE_USER',
                'description' => 'Menambahkan akun pengguna baru: staf_operasional (staf@example.com) dengan role Inputer',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_name' => 'Approver',
                'user_role' => 'Approver',
                'action' => 'APPROVE_TRANSACTION',
                'description' => 'Memberikan persetujuan akhir (Approved) untuk transaksi TRX-001',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                'created_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($logs as $log) {
            AuditLog::create($log);
        }
    }
}
