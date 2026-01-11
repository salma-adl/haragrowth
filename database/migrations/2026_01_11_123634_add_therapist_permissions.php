<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view therapists',
            'create therapists',
            'edit therapists',
            'delete therapists',
            'view therapist schedules',
            'create therapist schedules',
            'edit therapist schedules',
            'delete therapist schedules',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        $role = Role::where('name', 'therapist')->first();

        if ($role) {
            $role->givePermissionTo($permissions);
        }
        
        // Also give to admin if exists
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'view therapists',
            'create therapists',
            'edit therapists',
            'delete therapists',
            'view therapist schedules',
            'create therapist schedules',
            'edit therapist schedules',
            'delete therapist schedules',
        ];
        
        // We generally don't delete permissions in down() to avoid data loss if they are used elsewhere,
        // but strictly speaking we could revoke them. 
        // For safety, I'll leave them.
    }
};
