<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(UserRoleSeeder::class);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/transactions');
        $response->assertRedirect('/login');
    }

    public function test_inputer_without_check_permission_receives_403_on_checker_endpoint(): void
    {
        $inputer = User::factory()->create();
        $inputer->assignRole('Inputer');

        $response = $this->actingAs($inputer)->get('/transactions/checker');
        $response->assertStatus(403);
    }

    public function test_inputer_without_approve_permission_receives_403_on_approver_endpoint(): void
    {
        $inputer = User::factory()->create();
        $inputer->assignRole('Inputer');

        $response = $this->actingAs($inputer)->get('/transactions/approver');
        $response->assertStatus(403);
    }

    public function test_checker_cannot_create_transaction(): void
    {
        $checker = User::factory()->create();
        $checker->assignRole('Checker');

        $response = $this->actingAs($checker)->get('/transactions/create');
        $response->assertStatus(403);
    }

    public function test_inputer_can_only_edit_own_draft_transaction(): void
    {
        $inputer1 = User::factory()->create();
        $inputer1->assignRole('Inputer');

        $inputer2 = User::factory()->create();
        $inputer2->assignRole('Inputer');

        $txDraft = Transaction::create([
            'transaction_number' => 'TX-TEST-001',
            'description' => 'Draft Milik Inputer 1',
            'amount' => 100000,
            'status' => 'draft',
            'inputer_id' => $inputer1->id,
        ]);

        // Inputer 1 can access edit form
        $response = $this->actingAs($inputer1)->get("/transactions/{$txDraft->id}/edit");
        $response->assertStatus(200);

        // Inputer 2 receives 403 trying to edit Inputer 1's draft
        $response = $this->actingAs($inputer2)->get("/transactions/{$txDraft->id}/edit");
        $response->assertStatus(403);

        // If transaction is submitted, Inputer 1 cannot edit anymore (403 Forbidden)
        $txDraft->update(['status' => 'pending_check']);
        $response = $this->actingAs($inputer1)->get("/transactions/{$txDraft->id}/edit");
        $response->assertStatus(403);
    }

    public function test_inputer_cannot_delete_submitted_transaction(): void
    {
        $inputer = User::factory()->create();
        $inputer->assignRole('Inputer');

        $tx = Transaction::create([
            'transaction_number' => 'TX-TEST-002',
            'description' => 'Transaksi Sudah Diajukan',
            'amount' => 250000,
            'status' => 'pending_check',
            'inputer_id' => $inputer->id,
        ]);

        $response = $this->actingAs($inputer)->delete("/transactions/{$tx->id}");
        $response->assertStatus(403);
    }

    public function test_admin_can_correct_transaction_and_override_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $inputer = User::factory()->create();
        $inputer->assignRole('Inputer');

        $tx = Transaction::create([
            'transaction_number' => 'TX-TEST-003',
            'description' => 'Draft Transaksi',
            'amount' => 500000,
            'status' => 'draft',
            'inputer_id' => $inputer->id,
        ]);

        // Admin can access edit form of any status
        $response = $this->actingAs($admin)->get("/transactions/{$tx->id}/edit");
        $response->assertStatus(200);

        // Admin overrides status to approved
        $updateResponse = $this->actingAs($admin)->put("/transactions/{$tx->id}", [
            'transaction_number' => 'TX-TEST-003',
            'description' => 'Koreksi Transaksi oleh Admin',
            'amount' => 550000,
            'status' => 'approved',
        ]);

        $updateResponse->assertRedirect('/transactions');
        $this->assertDatabaseHas('transactions', [
            'id' => $tx->id,
            'status' => 'approved',
            'description' => 'Koreksi Transaksi oleh Admin',
            'amount' => 550000,
        ]);
    }
}
