<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CenterRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يعمل بعد RolesAndPermissionsSeeder
     * ويقوم بتحديث صلاحيات الأدوار الموجودة (الموحدة) فقط
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // تعيين tenant_id = null لجعل الأدوار موحدة
        setPermissionsTeamId(null);

        // 1. Define Permissions
        $permissions = [
            // Student Management
            'view students', 'create students', 'edit students', 'delete students',

            // Instructor Management
            'view instructors', 'create instructors', 'edit instructors', 'delete instructors',

            // Course Management
            'view courses', 'create courses', 'edit courses', 'delete courses',

            // Financials
            'view sales', 'create sales', 'edit sales', 'delete sales',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view billing', 'manage billing',

            // Operations
            'view schedule', 'manage schedule',
            'view attendance', 'take attendance',
            'view exams', 'manage exams',

            // Admin
            'manage users', // add/edit other staff
            'view reports',
            'view analytics',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Define Global Roles (tenant_id = null) and Assign Permissions
        // Center Admin (Gets everything except system-wide permissions)
        $adminRole = Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => null]);
        $adminRole->syncPermissions($permissions);

        // Secretary
        $secretaryRole = Role::firstOrCreate(['name' => 'secretary', 'guard_name' => 'web', 'tenant_id' => null]);
        $secretaryRole->syncPermissions([
            'view students', 'create students', 'edit students',
            'view instructors',
            'view courses',
            'view schedule', 'manage schedule',
            'view attendance', 'take attendance',
            'view exams',
            'view sales', 'create sales', // Can maybe sell courses
        ]);

        // Accountant
        $accountantRole = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web', 'tenant_id' => null]);
        $accountantRole->syncPermissions([
            'view sales', 'create sales', 'edit sales', 'delete sales',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view billing', 'manage billing',
            'view reports',
        ]);

        // Staff (Basic View Access)
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web', 'tenant_id' => null]);
        $staffRole->syncPermissions([
            'view students',
            'view courses',
            'view schedule',
        ]);

        // Instructor
        $instructorRole = Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web', 'tenant_id' => null]);
        $instructorRole->syncPermissions([
            'view courses',
            'edit courses',
            'view students',
            'view schedule',
            'view attendance',
            'take attendance',
            'view exams',
            'manage exams',
        ]);

        // Student
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web', 'tenant_id' => null]);
        $studentRole->syncPermissions([
            'view courses',
        ]);
    }
}
