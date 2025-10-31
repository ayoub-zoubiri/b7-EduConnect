<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'create-course',
            'edit-course',
            'delete-course',
            'view-course',
            'enroll-course',
            'manage-users',
            'view-users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Admin has all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Teacher permissions
        $teacherRole->givePermissionTo([
            'create-course',
            'edit-course',
            'delete-course',
            'view-course',
        ]);

        // Student permissions
        $studentRole->givePermissionTo([
            'view-course',
            'enroll-course',
        ]);
    }
}