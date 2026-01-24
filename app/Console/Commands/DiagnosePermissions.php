<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DiagnosePermissions extends Command
{
    /**
     * اسم ووصف الأمر
     */
    protected $signature = 'permissions:diagnose';
    protected $description = 'تشخيص نظام الصلاحيات والأدوار - Diagnose the permissions system';

    public function handle()
    {
        $this->info('🔍 بدء تشخيص نظام الصلاحيات...');
        $this->newLine();

        // 1. فحص الأدوار
        $this->checkRoles();
        
        // 2. فحص الصلاحيات
        $this->checkPermissions();
        
        // 3. فحص المستخدمين
        $this->checkUsers();
        
        // 4. فحص التضارب
        $this->checkConflicts();
        
        $this->newLine();
        $this->info('✅ انتهى التشخيص!');
    }

    protected function checkRoles()
    {
        $this->info('📋 الأدوار (Roles):');
        $roles = Role::withCount(['permissions', 'users'])->get();
        
        if ($roles->isEmpty()) {
            $this->warn('⚠️  لا توجد أدوار! قم بتشغيل Seeder');
            $this->line('   php artisan db:seed --class=RolesAndPermissionsSeeder');
        } else {
            $this->table(
                ['الدور', 'عدد الصلاحيات', 'عدد المستخدمين'],
                $roles->map(fn($r) => [$r->name, $r->permissions_count, $r->users_count])
            );
        }
        $this->newLine();
    }

    protected function checkPermissions()
    {
        $this->info('🔐 الصلاحيات (Permissions):');
        $permissions = Permission::select(['id', 'name'])->get();
        
        if ($permissions->isEmpty()) {
            $this->warn('⚠️  لا توجد صلاحيات! قم بتشغيل Seeder');
        } else {
            $this->line("عدد الصلاحيات الكلي: {$permissions->count()}");
            
            // تجميع حسب الموارد
            $grouped = $permissions->groupBy(function($item) {
                return explode(' ', $item->name)[1] ?? 'other';
            });
            
            foreach ($grouped as $resource => $perms) {
                $this->line("  • {$resource}: " . $perms->pluck('name')->implode(', '));
            }
        }
        $this->newLine();
    }

    protected function checkUsers()
    {
        $this->info('👥 المستخدمين:');
        
        $usersWithRoles = User::has('roles')->count();
        $usersWithoutRoles = User::doesntHave('roles')->count();
        
        $this->line("مستخدمين لديهم أدوار: {$usersWithRoles}");
        $this->line("مستخدمين بدون أدوار: {$usersWithoutRoles}");
        
        if ($usersWithoutRoles > 0) {
            $this->warn("⚠️  يوجد {$usersWithoutRoles} مستخدم بدون أدوار!");
            $users = User::doesntHave('roles')->limit(5)->get(['id', 'name', 'email']);
            $this->table(['ID', 'الاسم', 'البريد'], $users->toArray());
        }
        $this->newLine();
    }

    protected function checkConflicts()
    {
        $this->info('⚠️  فحص التضاربات:');
        
        // فحص العمود role
        $usersWithRoleColumn = User::whereNotNull('role')->count();
        if ($usersWithRoleColumn > 0) {
            $this->warn("⚠️  يوجد {$usersWithRoleColumn} مستخدم لديهم قيمة في عمود 'role' القديم");
            $this->line("   هذا قد يتعارض مع Spatie Roles!");
            
            // عرض عينة
            $sample = User::whereNotNull('role')->limit(3)->get(['id', 'name', 'role']);
            $this->table(['ID', 'الاسم', 'Role (العمود القديم)'], $sample->toArray());
            
            $this->newLine();
            $this->line('💡 الحل المقترح:');
            $this->line('   1. قم بتشغيل: php artisan permissions:sync-old-roles');
            $this->line('   2. أو احذف العمود تماماً إذا كنت تستخدم Spatie فقط');
        } else {
            $this->info('✅ لا توجد تضاربات في عمود role');
        }
        
        // فحص Cache
        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->getCacheStore();
            $this->info('✅ Cache النظام يعمل');
        } catch (\Exception $e) {
            $this->error('❌ مشكلة في Cache: ' . $e->getMessage());
        }
    }
}
