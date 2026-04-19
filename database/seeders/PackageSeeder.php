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
                'description_fr' => 'Essayez la plateforme sans aucun engagement.',
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
                'stripe_price_id' => 'price_1TAJ0FF1Qx8XSbKrrJ65PIni',
                'price' => 450.00, // Monthly
                'term_price' => 1450.00, // 150 days
                'yearly_price' => 2500.00, // 365 days
                'old_price' => 1800.00,
                'duration_in_days' => 150,
                'description' => 'للمدرسين المستقلين والمجموعات الصغيرة.',
                'description_en' => 'Perfect for individual tutors.',
                'description_fr' => 'Parfait pour les tuteurs individuels.',
                'is_featured' => false,
                'regional_prices' => [
                    'default' => ['amount' => 15, 'currency' => 'USD', 'term_price' => 49, 'yearly_price' => 85, 'old_price' => 60],
                    'EG' => ['amount' => 450, 'currency' => 'EGP', 'term_price' => 1450, 'yearly_price' => 2500, 'old_price' => 1800],
                    'SA' => ['amount' => 60, 'currency' => 'SAR', 'term_price' => 185, 'yearly_price' => 320, 'old_price' => 220],
                    'AE' => ['amount' => 60, 'currency' => 'AED', 'term_price' => 185, 'yearly_price' => 320, 'old_price' => 220],
                    'FR' => ['amount' => 14, 'currency' => 'EUR', 'term_price' => 45, 'yearly_price' => 80, 'old_price' => 55],
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
                'stripe_price_id' => 'price_1TAJ5uF1Qx8XSbKrAvkraZtj',
                'price' => 950.00, // Monthly
                'term_price' => 3450.00, // 150 days
                'yearly_price' => 6000.00, // 365 days
                'old_price' => 4200.00,
                'duration_in_days' => 150,
                'badge' => 'الأكثر طلباً',
                'description' => 'للمراكز التعليمية المتنامية.',
                'description_en' => 'Best for growing centers.',
                'description_fr' => 'Idéal pour les centres en pleine croissance.',
                'is_featured' => true,
                'discount_label' => 'وفر 20%',
                'regional_prices' => [
                    'default' => ['amount' => 30, 'currency' => 'USD', 'term_price' => 99, 'yearly_price' => 170, 'old_price' => 120],
                    'EG' => ['amount' => 950, 'currency' => 'EGP', 'term_price' => 3450, 'yearly_price' => 6000, 'old_price' => 4200],
                    'SA' => ['amount' => 120, 'currency' => 'SAR', 'term_price' => 450, 'yearly_price' => 780, 'old_price' => 550],
                    'AE' => ['amount' => 120, 'currency' => 'AED', 'term_price' => 450, 'yearly_price' => 780, 'old_price' => 550],
                    'FR' => ['amount' => 28, 'currency' => 'EUR', 'term_price' => 95, 'yearly_price' => 165, 'old_price' => 110],
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
                'stripe_price_id' => 'price_1TAJ6dF1Qx8XSbKrz6RRqwS7',
                'price' => 1950.00, // Monthly
                'term_price' => 6950.00, // 150 days
                'yearly_price' => 12000.00, // 365 days
                'old_price' => 8500.00,
                'duration_in_days' => 150,
                'description' => 'للرشكات التعليمية الكبرى والفروع.',
                'description_en' => 'For large chains and organizations.',
                'description_fr' => 'Pour les grandes chaînes et organisations.',
                'is_featured' => false,
                'regional_prices' => [
                    'default' => ['amount' => 60, 'currency' => 'USD', 'term_price' => 199, 'yearly_price' => 340, 'old_price' => 250],
                    'EG' => ['amount' => 1950, 'currency' => 'EGP', 'term_price' => 6950, 'yearly_price' => 12000, 'old_price' => 8500],
                    'SA' => ['amount' => 250, 'currency' => 'SAR', 'term_price' => 950, 'yearly_price' => 1650, 'old_price' => 1200],
                    'AE' => ['amount' => 250, 'currency' => 'AED', 'term_price' => 950, 'yearly_price' => 1650, 'old_price' => 1200],
                    'FR' => ['amount' => 55, 'currency' => 'EUR', 'term_price' => 185, 'yearly_price' => 320, 'old_price' => 230],
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
