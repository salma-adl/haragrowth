<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);
        
        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'create roles']);
        Permission::firstOrCreate(['name' => 'edit roles']);
        Permission::firstOrCreate(['name' => 'delete roles']);

        Permission::firstOrCreate(['name' => 'view permissions']);
        Permission::firstOrCreate(['name' => 'create permissions']);
        Permission::firstOrCreate(['name' => 'edit permissions']);
        Permission::firstOrCreate(['name' => 'delete permissions']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
                'view users',
                'create users',
                'edit users',
                'delete users',
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
                'view permissions',
                'create permissions',
                'edit permissions',
                'delete permissions'
            ]);

        $therapistRole = Role::firstOrCreate(['name' => 'therapist']);
        $therapistRole->givePermissionTo([
                'view users',
                'edit users',
                'view roles',
                'view permissions',
            ]);
    }
}
