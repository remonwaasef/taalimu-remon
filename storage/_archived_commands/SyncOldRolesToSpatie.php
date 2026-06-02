<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncOldRolesToSpatie extends Command
{
    protected $signature = 'permissions:sync-old-roles {--dry-run : عرض التغييرات فقط بدون تطبيقها}';
    protected $description = 'مزامنة الأدوار القديمة من عمود role إلى Spatie Roles';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('🔍 وضع العرض فقط (Dry Run) - لن يتم حفظ أي تغييرات');
        }
        
        $this->info('بدء مزامنة الأدوار...');
        $this->newLine();

        // جلب المستخدمين الذين لديهم قيمة في عمود role
        $usersWithOldRole = User::whereNotNull('role')->get();
        
        if ($usersWithOldRole->isEmpty()) {
            $this->info('✅ لا يوجد مستخدمين بأدوار قديمة للمزامنة');
            return 0;
        }

        $this->line("وجدت {$usersWithOldRole->count()} مستخدم للمزامنة");
        $this->newLine();

        $stats = [
            'synced' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        foreach ($usersWithOldRole as $user) {
            $oldRole = $user->role;
            
            // تحقق من وجود الدور في Spatie
            $roleExists = Role::where('name', $oldRole)->exists();
            
            if (!$roleExists) {
                $this->warn("⚠️  الدور '{$oldRole}' غير موجود في Spatie Roles");
                $this->line("   المستخدم: {$user->name} (ID: {$user->id})");
                
                if ($this->confirm("هل تريد إنشاء الدور '{$oldRole}'؟", true)) {
                    if (!$dryRun) {
                        Role::create(['name' => $oldRole, 'guard_name' => 'web']);
                        $this->info("✅ تم إنشاء الدور '{$oldRole}'");
                    } else {
                        $this->line("   [DRY RUN] سيتم إنشاء الدور '{$oldRole}'");
                    }
                } else {
                    $stats['skipped']++;
                    continue;
                }
            }

            // تعيين الدور
            if (!$user->hasRole($oldRole)) {
                if (!$dryRun) {
                    try {
                        $user->assignRole($oldRole);
                        $this->line("✅ {$user->name}: تم تعيين دور '{$oldRole}'");
                        $stats['synced']++;
                    } catch (\Exception $e) {
                        $this->error("❌ خطأ في تعيين الدور للمستخدم {$user->name}: {$e->getMessage()}");
                        $stats['errors']++;
                    }
                } else {
                    $this->line("   [DRY RUN] {$user->name}: سيتم تعيين دور '{$oldRole}'");
                    $stats['synced']++;
                }
            } else {
                $this->line("⏭️  {$user->name}: لديه الدور بالفعل");
                $stats['skipped']++;
            }
        }

        // عرض الإحصائيات
        $this->newLine();
        $this->info('📊 الإحصائيات:');
        $this->table(
            ['العملية', 'العدد'],
            [
                ['تمت المزامنة', $stats['synced']],
                ['تم التخطي', $stats['skipped']],
                ['أخطاء', $stats['errors']],
            ]
        );

        if ($dryRun) {
            $this->newLine();
            $this->info('💡 لتطبيق التغييرات، قم بتشغيل الأمر بدون --dry-run:');
            $this->line('   php artisan permissions:sync-old-roles');
        } else {
            $this->newLine();
            $this->info('✅ اكتملت المزامنة!');
            
            if ($stats['synced'] > 0) {
                $this->newLine();
                $this->line('💡 الخطوة التالية:');
                $this->line('   1. تحقق من أن كل شيء يعمل بشكل صحيح');
                $this->line('   2. إذا كنت متأكداً، يمكنك حذف عمود role:');
                $this->line('      php artisan make:migration remove_role_from_users_table');
            }
        }

        return 0;
    }
}
