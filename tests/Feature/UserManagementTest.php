<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(UserRoleSeeder::class);
    }

    public function test_unauthorized_user_cannot_access_user_management(): void
    {
        $inputer = User::where('email', 'inputer@example.com')->first();

        $response = $this->actingAs($inputer)->get('/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_user_list_and_statistics(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna & Akun', false);
        $response->assertSee('Total Akun');
    }

    public function test_admin_can_create_new_user_with_spatie_role(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->post('/users', [
            'name'                  => 'Budi Operator',
            'email'                 => 'budi.operator@example.com',
            'password'              => 'OperatorSecure123!',
            'role'                  => 'Inputer',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi.operator@example.com',
            'name'  => 'Budi Operator',
        ]);

        $newUser = User::where('email', 'budi.operator@example.com')->first();
        $this->assertTrue($newUser->hasRole('Inputer'));
    }

    public function test_admin_can_update_user_identity_role_and_reset_password(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $targetUser = User::where('email', 'user@example.com')->first();

        $response = $this->actingAs($admin)->put(route('users.update', $targetUser), [
            'name'     => 'User Terkoreksi',
            'email'    => 'user.updated@example.com',
            'password' => 'PasswordBaru456!',
            'role'     => 'Checker',
        ]);

        $response->assertRedirect(route('users.index'));
        $targetUser->refresh();

        $this->assertEquals('User Terkoreksi', $targetUser->name);
        $this->assertEquals('user.updated@example.com', $targetUser->email);
        $this->assertTrue(Hash::check('PasswordBaru456!', $targetUser->password));
        $this->assertTrue($targetUser->hasRole('Checker'));
    }

    public function test_active_logged_in_user_cannot_delete_their_own_account(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Kamu tidak dapat menghapus akunmu sendiri yang sedang aktif.');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_superadmin_role_cannot_be_demoted(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $superadmin = User::where('email', 'superadmin@example.com')->first();

        $response = $this->actingAs($admin)->put(route('users.update', $superadmin), [
            'name'  => $superadmin->name,
            'email' => $superadmin->email,
            'role'  => 'Inputer',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');
        $superadmin->refresh();
        $this->assertTrue($superadmin->hasRole('Superadmin'));
    }

    public function test_superadmin_and_admin_can_view_all_transactions_mode(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $inputer = User::where('email', 'inputer@example.com')->first();

        Transaction::create([
            'transaction_number' => 'TX-ADMIN-VIEW-01',
            'description'        => 'Transaksi oleh Inputer',
            'amount'             => 500000,
            'status'             => 'draft',
            'inputer_id'         => $inputer->id,
        ]);

        // Admin sees all transactions
        $responseAdmin = $this->actingAs($admin)->get(route('transactions.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Semua Transaksi');
        $responseAdmin->assertSee('TX-ADMIN-VIEW-01');

        // Inputer sees own transactions mode
        $responseInputer = $this->actingAs($inputer)->get(route('transactions.index'));
        $responseInputer->assertStatus(200);
        $responseInputer->assertSee('Transaksi Saya');
        $responseInputer->assertSee('TX-ADMIN-VIEW-01');
    }
}
