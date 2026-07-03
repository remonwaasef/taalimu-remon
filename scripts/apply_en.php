<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Arr;

$langDirs = [
    'core' => __DIR__.'/resources/lang',
    'center' => __DIR__.'/Modules/Center/resources/lang',
];

$translations = [
    'en' => [
        'core/admin.php' => [
            'system_features_tab' => 'System Features',
            'system_features_management' => 'System Features Management',
            'system_features_note' => 'Control the technical features that appear in the comparison table',
            'feature_name' => 'Feature Name',
            'feature_code' => 'Feature Code',
            'type' => 'Type',
            'category' => 'Category',
            'limit_type' => 'Numeric (Limit)',
            'boolean_type' => 'Yes/No (Boolean)',
            'new_package' => 'New Package',
            'new_feature' => 'New Feature',
            'save_all' => 'Save All',
            'regional_prices' => 'Regional Prices',
            'auto_detected' => 'Auto Detected',
        ],
        'core/features.php' => [
            'whatsapp_alerts' => 'Automated WhatsApp Alerts',
            'multi_branch' => 'Multi-branch Support',
        ],
        'core/landing.php' => [
            'pricing.days' => 'Day',
            'pricing.plans.basic.badge' => 'Most Popular',
            'pricing.plans.pro.badge' => 'For Businesses',
            'pricing.comparison.categories.core.features.max_students' => 'Students Limit',
            'pricing.comparison.categories.core.features.max_admins' => 'Admin Users',
            'pricing.comparison.categories.core.features.max_courses' => 'Courses Limit',
            'pricing.comparison.categories.core.features.storage_limit' => 'Storage Limit',
            'pricing.comparison.categories.core.features.max_instructors' => 'Instructors Limit',
            'pricing.comparison.categories.core.features.max_classrooms' => 'Classrooms Limit',
            'pricing.comparison.categories.core.features.advanced_reports' => 'Advanced Reports',
            'pricing.comparison.categories.core.features.priority_support' => 'Priority Tech Support',
            'pricing.comparison.categories.core.features.multi_branch' => 'Multi-branch Support',
            'pricing.comparison.categories.smart.features.api_access' => 'API Access',
            'pricing.comparison.categories.smart.features.whatsapp_alerts' => 'Automated WhatsApp Alerts',
            'pricing.comparison.categories.smart.features.sms_alerts' => 'SMS Alerts',
            'pricing.comparison.categories.analysis.features.financial_reports' => 'Financial Reports',
            'pricing.comparison.categories.analysis.features.technical_support' => 'Tech Support',
            'pricing.comparison.plans.free.branches' => false,
            'pricing.comparison.plans.free.sms' => false,
            'pricing.comparison.plans.basic.branches' => false,
        ],
        'core/validation.php' => [
            'attributes.center_name' => 'Center Name',
            'attributes.name' => 'Full Name',
            'attributes.email' => 'Email Address',
            'attributes.password' => 'Password',
            'attributes.plan' => 'Plan',
        ],
        'center/analytics.php' => [
            'general' => 'Analytics',
        ],
        'center/classrooms.php' => [
            'students_count' => '{0} Student|{1} 1 Student|[2,*] :count Students',
            'add_asset' => 'Add Asset to this Classroom',
            'assets_count' => 'Assets & Inventory',
        ],
        'center/courses.php' => [
            'schedules' => 'Schedules',
            'enroll_student' => 'Enroll Student',
        ],
        'center/dashboard.php' => [
            'enroll_student' => 'Enroll Student',
            'report' => 'Report',
            'system_status' => 'System Status',
            'real_time_monitoring' => 'Real-time monitoring is active',
            'vs_last_month' => 'vs Last Month',
            'models.package' => 'Package',
            'models.coupon' => 'Discount Coupon',
            'launchpad.title' => 'Welcome :name! Let\'s get your center ready 🚀',
            'launchpad.subtitle' => 'Complete these simple steps to start your educational journey',
            'launchpad.progress' => 'Setup Progress',
            'launchpad.action' => 'Start Now',
            'launchpad.steps.education_system.title' => 'Education System',
            'launchpad.steps.education_system.desc' => 'Draw the roadmap.. Choose the stages and grades for your center.',
            'launchpad.steps.instructor.title' => 'Add your first Instructor',
            'launchpad.steps.instructor.desc' => 'Build your outstanding team.. Add creative instructors to present your content.',
            'launchpad.steps.course.title' => 'Create your first Course',
            'launchpad.steps.course.desc' => 'Start the journey.. Link the instructor with students and launch your first group.',
            'launchpad.steps.student.title' => 'Enroll your first Student',
            'launchpad.steps.student.desc' => 'Welcome your new students.. Start enrolling students in your courses.',
            'launchpad.steps.attendance.title' => 'Take Attendance',
            'launchpad.steps.attendance.desc' => 'Monitor discipline.. Start tracking the attendance and absence of your students accurately.',
        ],
        'center/instructors.php' => [
            'status' => 'Status',
            'commission_rate' => 'Commission Rate (%)',
            'national_id' => 'National ID / Identity',
            'gender' => 'Gender',
            'male' => 'Male',
            'female' => 'Female',
            'hiring_date' => 'Hiring Date',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_hold' => 'On Hold',
            'phone' => 'Phone Number',
            'bio' => 'Biography',
            'show' => 'Show Details',
        ],
        'center/sales.php' => [
            'select_courses' => 'Select Courses',
            'cart' => 'Cart',
            'empty_cart' => 'Cart is empty',
            'payment_method' => 'Payment Method',
            'paid_amount' => 'Paid Amount',
            'notes' => 'Notes',
            'complete_sale' => 'Complete Sale',
            'cash' => 'Cash',
            'card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'select_student' => 'Select Student',
            'item_already_in_cart' => 'This course is already in the cart',
            'please_select_student' => 'Please select a student',
        ],
        'center/settings.php' => [
            'academic.attendance_rules' => 'Attendance & Lateness Rules',
            'academic.late_levels' => 'Lateness Levels',
            'academic.late_levels_help' => 'You can define different levels of lateness. The system will compare the scan time with the session start time.',
            'academic.threshold_minutes' => 'Minutes (after start)',
            'academic.level_label' => 'Status Name (e.g., Mild Delay)',
            'academic.add_level' => 'Add Lateness Level',
            'academic.confirm_delete_level' => 'Are you sure you want to delete this lateness level?',
        ],
        'center/sidebar.php' => [
            'expenses' => 'Expenses',
            'questions_bank' => 'Question Bank',
            'leaderboard' => 'Leaderboard',
            'student_updated' => 'Student updated successfully',
            'student_deleted' => 'Student deleted successfully',
            'assets' => 'Assets & Inventory',
            'school_management' => 'School Management',
        ],
        'center/students.php' => [
            'export_file' => 'Export File',
        ],
    ],
];

function arrayToCode($array, $indent = 1)
{
    if (empty($array)) {
        return '[]';
    }
    $code = "[\n";
    $spaces = str_repeat('    ', $indent);
    foreach ($array as $key => $value) {
        $keyFormatted = is_string($key) ? "'".addslashes($key)."'" : $key;
        $code .= $spaces.$keyFormatted.' => ';
        if (is_array($value)) {
            $code .= arrayToCode($value, $indent + 1).",\n";
        } elseif (is_string($value)) {
            $code .= "'".addslashes($value)."',\n";
        } elseif (is_bool($value)) {
            $code .= ($value ? 'true' : 'false').",\n";
        } elseif (is_numeric($value)) {
            $code .= $value.",\n";
        } else {
            $code .= "null,\n";
        }
    }
    $code .= str_repeat('    ', $indent - 1).']';

    return $code;
}

foreach ($translations as $locale => $files) {
    foreach ($files as $fileKey => $keysToUpdate) {
        [$type, $filename] = explode('/', $fileKey);
        $baseDir = $langDirs[$type];
        $filePath = $baseDir.'/'.$locale.'/'.$filename;

        if (! file_exists($filePath)) {
            if (! is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
            $currentData = [];
        } else {
            $currentData = require $filePath;
        }

        foreach ($keysToUpdate as $dotKey => $value) {
            Arr::set($currentData, $dotKey, $value);
        }

        $code = "<?php\n\nreturn ".arrayToCode($currentData).";\n";
        file_put_contents($filePath, $code);
        echo "Updated $filePath\n";
    }
}

echo "English translations applied successfully!\n";
