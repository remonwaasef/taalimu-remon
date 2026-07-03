<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === حذف الأدوار المكررة (المرتبطة بـ tenant) والإبقاء على الأدوار الموحدة فقط ===
        $this->cleanupDuplicateRoles();

        // === إنشاء صلاحيات تفصيلية (CRUD) ===
        
        // صلاحيات المراكز (للـ Super Admin فقط)
        $centerPermissions = [
            'view centers',
            'create centers',
            'edit centers',
            'delete centers',
            'suspend centers',
            'manage centers', // keep for backward compatibility
        ];
        
        // صلاحيات الطلاب
        $studentPermissions = [
            'view students',
            'create students',
            'edit students',
            'delete students',
            'manage students', // keep for backward compatibility
        ];
        
        // صلاحيات المدرسين
        $instructorPermissions = [
            'view instructors',
            'create instructors',
            'edit instructors',
            'delete instructors',
            'manage instructors', // keep for backward compatibility
        ];
        
        // صلاحيات الدورات
        $coursePermissions = [
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',
            'manage courses', // keep for backward compatibility
        ];
        
        // صلاحيات المبيعات والفواتير
        $salesPermissions = [
            'view sales',
            'create sales',
            'edit sales',
            'delete sales',
        ];
        
        $expensePermissions = [
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
        ];
        
        $billingPermissions = [
            'view billing',
            'manage billing',
        ];
        
        // صلاحيات الجداول والحضور
        $schedulePermissions = [
            'view schedule',
            'manage schedule',
        ];
        
        $attendancePermissions = [
            'view attendance',
            'take attendance',
        ];
        
        // صلاحيات الامتحانات
        $examPermissions = [
            'view exams',
            'manage exams',
        ];
        
        // صلاحيات التقارير والتحليلات
        $reportPermissions = [
            'view reports',
            'view analytics',
        ];
        
        // صلاحيات المستخدمين والإعدادات
        $systemPermissions = [
            'manage users',
            'manage settings',
        ];

        // === إنشاء كل الصلاحيات ===
        $allPermissions = array_merge(
            $centerPermissions,
            $studentPermissions,
            $instructorPermissions,
            $coursePermissions,
            $salesPermissions,
            $expensePermissions,
            $billingPermissions,
            $schedulePermissions,
            $attendancePermissions,
            $examPermissions,
            $reportPermissions,
            $systemPermissions
        );
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // === إنشاء الأدوار الموحدة (بدون tenant_id) ===
        // تعيين tenant_id = null لجعل الأدوار موحدة لجميع المراكز
        setPermissionsTeamId(null);
        
        // 1. Super Admin - كل الصلاحيات
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web', 'tenant_id' => null]);
        $superAdmin->syncPermissions(Permission::all());
        
        // 2. Center Admin - صلاحيات إدارة المركز
        $centerAdmin = Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => null]);
        $centerAdmin->syncPermissions(array_merge(
            $studentPermissions,
            $instructorPermissions,
            $coursePermissions,
            $salesPermissions,
            $expensePermissions,
            $billingPermissions,
            $schedulePermissions,
            $attendancePermissions,
            $examPermissions,
            $reportPermissions,
            ['manage users', 'manage settings'] // إدارة مستخدمي المركز والإعدادات
        ));
        
        // 3. Instructor - صلاحيات محدودة
        $instructor = Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web', 'tenant_id' => null]);
        $instructor->syncPermissions([
            'view courses',
            'edit courses', // الدورات الخاصة به فقط
            'view students',
            'view schedule',
            'view attendance',
            'take attendance',
            'view exams',
            'manage exams',
        ]);
        
        // 4. Student - عرض فقط
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web', 'tenant_id' => null]);
        $student->syncPermissions([
            'view courses',
        ]);
        
        // 5. Secretary (سكرتير) - إدارة الطلاب والجداول
        $secretary = Role::firstOrCreate(['name' => 'secretary', 'guard_name' => 'web', 'tenant_id' => null]);
        $secretary->syncPermissions([
            'view students',
            'create students',
            'edit students',
            'view instructors',
            'view courses',
            'view schedule',
            'manage schedule',
            'view attendance',
            'take attendance',
            'view sales',
            'create sales',
        ]);
        
        // 6. Accountant (محاسب) - إدارة المالية فقط
        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web', 'tenant_id' => null]);
        $accountant->syncPermissions(array_merge(
            $salesPermissions,
            $expensePermissions,
            $billingPermissions,
            ['view reports', 'view analytics']
        ));
        
        // 7. Parent (ولي أمر) - متابعة أبنائه
        $parentRole = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web', 'tenant_id' => null]);
        $parentRole->syncPermissions([
            'view courses',
            'view attendance',
            'view exams',
            'view reports',
        ]);
        
        // 8. Staff (موظف عام) - صلاحيات محدودة جداً
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web', 'tenant_id' => null]);
        $staff->syncPermissions([
            'view students',
            'view courses',
            'view schedule',
        ]);
    }

    /**
     * يضمن وجود الأدوار والصلاحيات الموحدة عند تسجيل مستأجر جديد.
     * الأدوار عالمية (tenant_id = null) فلا تُنشأ نسخ لكل مستأجر؛
     * الاستدعاء idempotent ويهيئ أول تسجيل على قاعدة بيانات فارغة.
     */
    public static function seedForTenant(?int $tenantId = null): void
    {
        (new self)->run();
    }

    /**
     * حذف الأدوار المكررة (المرتبطة بـ tenant معين) والإبقاء على الموحدة فقط
     */
    protected function cleanupDuplicateRoles(): void
    {
        $globalRoleNames = [
            'super_admin', 'center_admin', 'instructor', 'student', 
            'secretary', 'accountant', 'staff'
        ];

        // حذف الأدوار المكررة (التي لديها tenant_id غير null)
        DB::table('roles')
            ->whereIn('name', $globalRoleNames)
            ->whereNotNull('tenant_id')
            ->delete();
    }
}
