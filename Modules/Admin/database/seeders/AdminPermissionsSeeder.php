<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define system roles (tenant_id = null)
        $roles = [
            'super_admin'     => 'صلاحيات كاملة للمنصة',
            'support_agent'   => 'إدارة تذاكر الدعم والعملاء',
            'finance_manager' => 'إدارة الاشتراكات والتقارير المالية',
            'content_manager' => 'إدارة إعدادات المنصة والمحتوى العام',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web', 'tenant_id' => null]
            );
        }

        // Define basic permissions for the Admin group
        $permissions = [
            'admin.access' => 'دخول لوحة الإدارة',
            'admin.tenants.view' => 'عرض المراكز',
            'admin.tenants.manage' => 'إدارة المراكز والاشتراكات',
            'admin.tickets.manage' => 'إدارة تذاكر الدعم',
            'admin.settings.manage' => 'إدارة إعدادات المنصة',
            'admin.users.manage' => 'إدارة فريق العمل',
            'admin.roles.manage' => 'إدارة الأدوار والصلاحيات',
        ];

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // Assign all to super_admin
        $superAdmin = Role::where('name', 'super_admin')->whereNull('tenant_id')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(array_keys($permissions));
        }

        // Simple assignment for others (can be customized later via UI)
        $support = Role::where('name', 'support_agent')->whereNull('tenant_id')->first();
        if ($support) {
            $support->syncPermissions(['admin.access', 'admin.tickets.manage', 'admin.tenants.view']);
        }

        $finance = Role::where('name', 'finance_manager')->whereNull('tenant_id')->first();
        if ($finance) {
            $finance->syncPermissions(['admin.access', 'admin.tenants.manage', 'admin.tenants.view']);
        }
    }
}
