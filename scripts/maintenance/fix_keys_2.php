<?php

$files = [
    'd:/new project/antigravty/edu/edu/add_placeholders.php'
];

$mappings = [
    'اسم_الطالب' => 'student_name',
    'اسم_المركز' => 'center_name',
    'المبلغ_المدفوع' => 'amount_paid',
    'المبلغ_المتبقي' => 'remaining',
    'المبلغ' => 'amount',
    'تاريخ_الاستحقاق' => 'due_date',
    'اسم_المجموعة' => 'group_name',
    'سعر_الدورة' => 'course_price',
    'رابط_الدخول' => 'login_link',
    'كلمة_المرور' => 'password',
    'رقم_الهاتف' => 'phone',
    'اسم_ولي_الأمر' => 'parent_name',
    'المرحلة' => 'stage',
    'تاريخ_الدفع' => 'payment_date',
    'طريقة_الدفع' => 'payment_method'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        foreach ($mappings as $ar => $en) {
            $content = str_replace($ar, $en, $content);
        }
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
