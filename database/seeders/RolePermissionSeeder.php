<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            'submit transactions',
            'check transactions',
            'approve transactions',
            'reject transactions',
            'view audit logs',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roleNames = ['Superadmin', 'Admin', 'User', 'Inputer', 'Checker', 'Approver'];
        foreach ($roleNames as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $superadmin = Role::findByName('Superadmin', 'web');
        $admin = Role::findByName('Admin', 'web');
        $user = Role::findByName('User', 'web');
        $inputer = Role::findByName('Inputer', 'web');
        $checker = Role::findByName('Checker', 'web');
        $approver = Role::findByName('Approver', 'web');

        $superadmin->syncPermissions($permissions);

        $admin->syncPermissions([
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            'submit transactions',
            'view audit logs',
            'manage users',
        ]);

        $user->syncPermissions([
            'view transactions',
        ]);

        $inputer->syncPermissions([
            'view transactions',
            'create transactions',
            'edit transactions',
            'submit transactions',
        ]);

        $checker->syncPermissions([
            'view transactions',
            'check transactions',
        ]);

        $approver->syncPermissions([
            'view transactions',
            'approve transactions',
            'reject transactions',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
