<?php

/**
 * PRODUCTION DATABASE SYNC SCRIPT
 * This script bypasses Artisan to update the migrations table directly.
 */

use Illuminate\Support\Facades\DB;

// Load Laravel Bootstrap
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- PRODUCTION DATABASE SYNC START ---\n";

$migrations = [
    '0000_01_01_000000_create_tenants_table',
    '0000_01_01_000001_create_users_table',
    '0000_01_01_000002_create_branches_table',
    '0000_01_01_000004_create_guardians_table',
    '0000_01_01_000004_create_stages_table',
    '0000_01_01_000005_create_grades_table',
    '0000_01_01_000005_create_question_categories_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
    '2019_12_14_000001_create_personal_access_tokens_table',
    '2025_11_28_182754_create_students_table',
    '2025_11_28_183114_create_instructors_table',
    '2025_11_28_183115_create_courses_table',
    '2025_11_28_214425_create_permission_tables',
    '2025_11_29_025916_create_activity_log_table',
    '2025_11_29_030327_create_packages_table',
    '2025_11_29_030330_create_features_table',
    '2025_11_29_030334_create_package_features_table',
    '2025_11_29_030339_create_subscriptions_table',
    '2025_11_29_031350_create_invoices_table',
    '2025_11_29_031821_create_sections_table',
    '2025_11_29_031826_create_lessons_table',
    '2025_11_29_032014_create_enrollments_table',
    '2025_11_29_032018_create_lesson_progress_table',
    '2025_11_29_032709_create_quizzes_table',
    '2025_11_29_032710_create_questions_table',
    '2025_11_29_032711_create_question_options_table',
    '2025_11_29_032712_create_quiz_attempts_table',
    '2025_11_29_033623_create_assignments_table',
    '2025_11_29_033629_create_assignment_submissions_table',
    '2025_11_29_100000_create_classrooms_table',
    '2025_11_29_100001_create_schedules_table',
    '2025_11_29_111253_create_support_ticket_tables',
    '2025_11_29_111952_create_sales_tables',
    '2025_11_29_150903_create_attendances_table',
    '2025_12_05_180238_create_user_consents_table',
    '2026_01_03_200000_create_bookings_table',
    '2026_01_04_203910_create_notifications_table',
    '2026_01_05_000000_create_site_settings_table',
    '2026_01_05_064218_create_coupons_table',
    '2026_01_10_115430_create_payments_table',
    '2026_01_10_152638_create_expenses_table',
    '2026_01_10_154018_create_point_logs_table',
    '2026_01_10_154829_create_certificates_table',
    '2026_01_10_155314_create_course_resources_table',
    '2026_01_15_165451_create_course_instructor_table',
    '2026_02_03_143156_create_assets_table',
    '2026_02_03_210000_create_operation_issues_table',
    '2026_02_03_210001_create_issue_timeline_table',
    '2026_02_03_210002_create_issue_attachments_table',
];

try {
    echo "1. Clearing existing migration history...\n";
    DB::table('migrations')->delete(); // Using delete instead of truncate to be safer

    echo "2. Injecting new 50-file migration history...\n";
    $batch = 1;
    $data = [];
    foreach ($migrations as $migration) {
        $data[] = [
            'migration' => $migration,
            'batch' => $batch,
        ];
    }
    DB::table('migrations')->insert($data);

    echo "--- SUCCESS! Migrations table synchronized. ---\n";
    echo "You can now safely run php artisan migrate.\n";
} catch (\Exception $e) {
    echo "--- ERROR ---\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
