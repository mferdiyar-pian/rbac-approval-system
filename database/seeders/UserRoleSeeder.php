<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@example.com',
                'role' => 'Superadmin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'role' => 'User',
            ],
            [
                'name' => 'Inputer',
                'email' => 'inputer@example.com',
                'role' => 'Inputer',
            ],
            [
                'name' => 'Checker',
                'email' => 'checker@example.com',
                'role' => 'Checker',
            ],
            [
                'name' => 'Approver',
                'email' => 'approver@example.com',
                'role' => 'Approver',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('BelajarRBAC123!'),
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}

