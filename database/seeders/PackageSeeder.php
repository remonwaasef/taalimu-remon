<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Feature;
use App\Models\PackageFeature;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Features
        // We ensure 'name' is in Arabic for better default display
        $features = [
            ['name' => 'عدد الطلاب', 'code' => 'max_students', 'type' => 'limit', 'category' => 'core'],
            ['name' => 'عدد المشرفين', 'code' => 'max_admins', 'type' => 'limit', 'category' => 'core'],
            ['name' => 'عدد المدرسين', 'code' => 'max_instructors', 'type' => 'limit', 'category' => 'core'],
            ['name' => 'عدد الكورسات', 'code' => 'max_courses', 'type' => 'limit', 'category' => 'core'],
            ['name' => 'عدد القاعات', 'code' => 'max_classrooms', 'type' => 'limit', 'category' => 'core'],
            ['name' => 'تعدد الفروع', 'code' => 'multi_branch', 'type' => 'boolean', 'category' => 'core'],
            ['name' => 'تنبيهات واتساب الآلية', 'code' => 'whatsapp_alerts', 'type' => 'boolean', 'category' => 'smart'],
            ['name' => 'تنبيهات SMS', 'code' => 'sms_alerts', 'type' => 'boolean', 'category' => 'smart'],
            ['name' => 'التقارير المالية', 'code' => 'financial_reports', 'type' => 'boolean', 'category' => 'analysis'],
            ['name' => 'الدعم الفني', 'code' => 'technical_support', 'type' => 'limit', 'category' => 'analysis'],
            ['name' => 'ربط برمجي API', 'code' => 'api_access', 'type' => 'boolean', 'category' => 'smart'],
            ['name' => 'تتبع الحضور', 'code' => 'attendance_tracking', 'type' => 'boolean', 'category' => 'analysis'],
            ['name' => 'الجداول اليومية', 'code' => 'daily_schedules', 'type' => 'boolean', 'category' => 'core'],
            ['name' => 'إدارة الامتحانات', 'code' => 'manage_exams', 'type' => 'boolean', 'category' => 'academic'],
            ['name' => 'صلاحيات متقدمة', 'code' => 'advanced_roles', 'type' => 'boolean', 'category' => 'core'],
            ['name' => 'بوابة الطالب', 'code' => 'student_portal', 'type' => 'boolean', 'category' => 'core'],
        ];

        foreach ($features as $featureData) {
            Feature::updateOrCreate(['code' => $featureData['code']], $featureData);
        }

        // 2. Create/Update Packages with Smart Regional Pricing
        // 'name' is Arabic by default, 'name_en' is English
        $plans = [
            [
                'name' => 'تجربة مجانية',
                'name_en' => 'Free Trial',
                'slug' => 'free-trial',
                'stripe_price_id' => 'price_free',
                'price' => 0.00,
                'yearly_price' => 0.00,
                'old_price' => 0.00,
                'duration_in_days' => 14,
                'trial_days' => 14,
                'description' => 'مثالية لتجربة المنصة.',
                'description_en' => 'Test drive the platform with no commitment.',
                'is_featured' => false,
                'regional_prices' => [
                    'default' => ['amount' => 0, 'currency' => 'USD'],
                    'EG' => ['amount' => 0, 'currency' => 'EGP'],
                    'SA' => ['amount' => 0, 'currency' => 'SAR'],
                    'AE' => ['amount' => 0, 'currency' => 'AED'],
                    'FR' => ['amount' => 0, 'currency' => 'EUR'],
                ],
                'features' => [
                    'max_students' => '50',
                    'max_instructors' => '2',
                    'max_courses' => '10',
                    'max_classrooms' => '2',
                    'max_admins' => '1',
                    'multi_branch' => 'false',
                    'whatsapp_alerts' => 'true',
                    'sms_alerts' => 'true',
                    'financial_reports' => 'true',
                    'technical_support' => 'إيميل',
                    'api_access' => 'false',
                    'attendance_tracking' => 'true',
                    'daily_schedules' => 'true',
                    'manage_exams' => 'true',
                    'advanced_roles' => 'false',
                    'student_portal' => 'true',
                ]
            ],
            [
                'name' => 'البداية',
                'name_en' => 'Starter',
                'slug' => 'basic', // Keeping slug 'basic'
                'stripe_price_id' => config('services.stripe.price_basic') ?: 'price_starter',
                'price' => 199.00,
                'yearly_price' => 499.00,
                'old_price' => 250.00,
                'duration_in_days' => 30,
                'description' => 'للمدرسين المستقلين والمجموعات الصغيرة.',
                'description_en' => 'Perfect for individual tutors.',
                'is_featured' => false,
                'regional_prices' => [
                    'default' => ['amount' => 25, 'currency' => 'USD', 'yearly_price' => 65, 'old_price' => 29],
                    'EG' => ['amount' => 199, 'currency' => 'EGP', 'yearly_price' => 499, 'old_price' => 250],
                    'SA' => ['amount' => 89, 'currency' => 'SAR', 'yearly_price' => 225, 'old_price' => 99],
                    'AE' => ['amount' => 89, 'currency' => 'AED', 'yearly_price' => 225, 'old_price' => 99],
                    'FR' => ['amount' => 25, 'currency' => 'EUR', 'yearly_price' => 65, 'old_price' => 29],
                ],
                'features' => [
                    'max_students' => '150',
                    'max_instructors' => '1',
                    'max_courses' => 'unlimited',
                    'max_classrooms' => '5',
                    'multi_branch' => 'false',
                    'whatsapp_alerts' => 'true',
                    'sms_alerts' => 'true',
                    'financial_reports' => 'basic',
                    'technical_support' => 'إيميل',
                    'api_access' => 'false',
                    'attendance_tracking' => 'true',
                    'daily_schedules' => 'true',
                    'manage_exams' => 'true',
                    'advanced_roles' => 'false',
                    'student_portal' => 'false',
                ]
            ],
            [
                'name' => 'النمو',
                'name_en' => 'Growth',
                'slug' => 'pro', // Keeping slug 'pro'
                'stripe_price_id' => config('services.stripe.price_pro') ?: 'price_growth',
                'price' => 499.00,
                'yearly_price' => 1250.00,
                'old_price' => 600.00,
                'duration_in_days' => 30,
                'badge' => 'الأكثر طلباً',
                'description' => 'للمراكز التعليمية المتنامية.',
                'description_en' => 'Best for growing centers.',
                'is_featured' => true,
                'discount_label' => 'وفر 20%',
                'regional_prices' => [
                    'default' => ['amount' => 55, 'currency' => 'USD', 'yearly_price' => 140, 'old_price' => 59],
                    'EG' => ['amount' => 499, 'currency' => 'EGP', 'yearly_price' => 1250, 'old_price' => 600],
                    'SA' => ['amount' => 185, 'currency' => 'SAR', 'yearly_price' => 450, 'old_price' => 199],
                    'AE' => ['amount' => 185, 'currency' => 'AED', 'yearly_price' => 450, 'old_price' => 199],
                    'FR' => ['amount' => 55, 'currency' => 'EUR', 'yearly_price' => 140, 'old_price' => 59],
                ],
                'features' => [
                    'max_students' => '500',
                    'max_instructors' => '10',
                    'max_admins' => '3',
                    'max_courses' => '-1',
                    'max_classrooms' => '10',
                    'multi_branch' => 'false',
                    'whatsapp_alerts' => 'true',
                    'sms_alerts' => 'true',
                    'financial_reports' => 'true',
                    'technical_support' => 'أولوية',
                    'api_access' => 'false',
                    'attendance_tracking' => 'true',
                    'daily_schedules' => 'true',
                    'manage_exams' => 'true',
                    'advanced_roles' => 'true',
                    'student_portal' => 'true',
                ]
            ],
            [
                'name' => 'المؤسسة',
                'name_en' => 'Institution',
                'slug' => 'enterprise',
                'stripe_price_id' => 'price_enterprise',
                'price' => 1249.00,
                'yearly_price' => 3100.00,
                'old_price' => 1500.00,
                'duration_in_days' => 30,
                'description' => 'للرشكات التعليمية الكبرى والفروع.',
                'description_en' => 'For large chains and organizations.',
                'is_featured' => false,
                'regional_prices' => [
                    'default' => ['amount' => 135, 'currency' => 'USD', 'yearly_price' => 330, 'old_price' => 149],
                    'EG' => ['amount' => 1249, 'currency' => 'EGP', 'yearly_price' => 3100, 'old_price' => 1500],
                    'SA' => ['amount' => 449, 'currency' => 'SAR', 'yearly_price' => 1100, 'old_price' => 499],
                    'AE' => ['amount' => 449, 'currency' => 'AED', 'yearly_price' => 1100, 'old_price' => 499],
                    'FR' => ['amount' => 135, 'currency' => 'EUR', 'yearly_price' => 330, 'old_price' => 149],
                ],
                'features' => [
                    'max_students' => '-1',
                    'max_instructors' => '-1',
                    'max_admins' => '-1',
                    'max_courses' => '-1',
                    'max_classrooms' => '-1',
                    'multi_branch' => 'true',
                    'whatsapp_alerts' => 'true',
                    'sms_alerts' => 'true',
                    'financial_reports' => 'true',
                    'technical_support' => 'مدير حساب',
                    'api_access' => 'true',
                    'attendance_tracking' => 'true',
                    'daily_schedules' => 'true',
                    'manage_exams' => 'true',
                    'advanced_roles' => 'true',
                    'student_portal' => 'true',
                ]
            ],
        ];

        foreach ($plans as $planData) {
            $features = $planData['features'];
            unset($planData['features']);

            $package = Package::updateOrCreate(['slug' => $planData['slug']], $planData);

            foreach ($features as $code => $value) {
                $feature = Feature::where('code', $code)->first();
                if ($feature) {
                    PackageFeature::updateOrCreate(
                        ['package_id' => $package->id, 'feature_id' => $feature->id],
                        ['value' => $value]
                    );
                }
            }
        }
    }
}
