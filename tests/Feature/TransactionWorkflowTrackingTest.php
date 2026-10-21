<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionWorkflowTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(UserRoleSeeder::class);
    }

    public function test_transaction_eager_loading_scope_loads_workflow_users_efficiently(): void
    {
        $inputer = User::where('email', 'inputer@example.com')->first();
        $checker = User::where('email', 'checker@example.com')->first();
        $approver = User::where('email', 'approver@example.com')->first();

        Transaction::create([
            'transaction_number' => 'TX-EAGER-01',
            'description'        => 'Test Eager Loading Scope',
            'amount'             => 1000000,
            'status'             => 'approved',
            'inputer_id'         => $inputer->id,
            'checker_id'         => $checker->id,
            'approver_id'        => $approver->id,
            'submitted_at'       => now(),
            'checked_at'         => now(),
            'approved_at'        => now(),
        ]);

        $tx = Transaction::withWorkflowUsers()->where('transaction_number', 'TX-EAGER-01')->first();

        $this->assertTrue($tx->relationLoaded('checker'));
        $this->assertTrue($tx->relationLoaded('approver'));
        $this->assertTrue($tx->relationLoaded('inputer'));
        $this->assertEquals('Checker', $tx->checker->name);
        $this->assertEquals('Approver', $tx->approver->name);
    }

    public function test_pending_approval_transaction_displays_status_badge_and_checker_name(): void
    {
        $inputer = User::where('email', 'inputer@example.com')->first();
        $checker = User::where('email', 'checker@example.com')->first();

        Transaction::create([
            'transaction_number' => 'TX-BADGE-01',
            'description'        => 'Transaksi Menunggu Approver',
            'amount'             => 750000,
            'status'             => 'pending_approval',
            'inputer_id'         => $inputer->id,
            'checker_id'         => $checker->id,
            'submitted_at'       => now()->subHour(),
            'checked_at'         => now(),
        ]);

        $response = $this->actingAs($inputer)->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertSee('Lolos Checker — Menunggu Approver', false);
        $response->assertSee('Diperiksa oleh:', false);
        $response->assertSee($checker->name);
    }

    public function test_reject_by_checker_records_checker_identity_timestamp_and_rejection_note(): void
    {
        $inputer = User::where('email', 'inputer@example.com')->first();
        $checker = User::where('email', 'checker@example.com')->first();

        $tx = Transaction::create([
            'transaction_number' => 'TX-REJECT-CHECKER-01',
            'description'        => 'Transaksi yang Ditolak Checker',
            'amount'             => 250000,
            'status'             => 'pending_check',
            'inputer_id'         => $inputer->id,
            'submitted_at'       => now(),
        ]);

        $response = $this->actingAs($checker)->post(route('transactions.reject-checker', $tx), [
            'rejection_note' => 'Lampiran kwitansi tidak jelas, mohon perbaiki.',
        ]);

        $response->assertRedirect(route('transactions.checker'));
        $tx->refresh();

        $this->assertEquals('rejected', $tx->status);
        $this->assertEquals($checker->id, $tx->checker_id);
        $this->assertNotNull($tx->checked_at);
        $this->assertEquals('Lampiran kwitansi tidak jelas, mohon perbaiki.', $tx->rejection_note);

        // Verify rejection note and status are displayed to Inputer
        $indexResponse = $this->actingAs($inputer)->get(route('transactions.index'));
        $indexResponse->assertSee('Ditolak');
        $indexResponse->assertSee('Lampiran kwitansi tidak jelas, mohon perbaiki.');
    }

    public function test_reject_by_approver_records_approver_identity_timestamp_and_rejection_note(): void
    {
        $inputer = User::where('email', 'inputer@example.com')->first();
        $checker = User::where('email', 'checker@example.com')->first();
        $approver = User::where('email', 'approver@example.com')->first();

        $tx = Transaction::create([
            'transaction_number' => 'TX-REJECT-APPROVER-01',
            'description'        => 'Transaksi yang Ditolak Approver',
            'amount'             => 5000000,
            'status'             => 'pending_approval',
            'inputer_id'         => $inputer->id,
            'checker_id'         => $checker->id,
            'submitted_at'       => now()->subHours(2),
            'checked_at'         => now()->subHour(),
        ]);

        $response = $this->actingAs($approver)->post(route('transactions.reject-approver', $tx), [
            'rejection_note' => 'Anggaran periode ini sudah habis, pending ke bulan depan.',
        ]);

        $response->assertRedirect(route('transactions.approver'));
        $tx->refresh();

        $this->assertEquals('rejected', $tx->status);
        $this->assertEquals($approver->id, $tx->approver_id);
        $this->assertNotNull($tx->approved_at);
        $this->assertEquals('Anggaran periode ini sudah habis, pending ke bulan depan.', $tx->rejection_note);

        // Verify rejection note is displayed
        $indexResponse = $this->actingAs($inputer)->get(route('transactions.index'));
        $indexResponse->assertSee('Ditolak');
        $indexResponse->assertSee('Anggaran periode ini sudah habis, pending ke bulan depan.');
    }
}
