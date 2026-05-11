<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'catalog.view', 'catalog.manage',
            'orders.view', 'orders.manage',
            'customers.view', 'customers.manage',
            'cms.view', 'cms.manage',
            'settings.manage',
        ];

        foreach ($permissions as $name) {
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $super = Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staff = Role::query()->firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $customer = Role::query()->firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $super->syncPermissions(Permission::query()->where('guard_name', 'web')->get());
        $admin->givePermissionTo(['catalog.view', 'catalog.manage', 'orders.view', 'orders.manage', 'customers.view', 'cms.view', 'cms.manage']);
        $staff->givePermissionTo(['catalog.view', 'orders.view', 'customers.view', 'cms.view']);
        $customer->syncPermissions([]);
    }
}
