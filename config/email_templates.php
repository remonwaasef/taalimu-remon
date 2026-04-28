<?php

/**
 * Default email template presets for welcome emails.
 * These are used as starting points; tenants can customize from Settings.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Available Template Variables
    |--------------------------------------------------------------------------
    | {student_name}    - Student's full name
    | {center_name}    - Center/Instructor name
    | {login_link}   - Platform login URL
    | {password}   - Generated temporary password
    | {phone}    - Student's phone number
    | {parent_name} - Guardian's name
    | {stage}       - Grade/level name
    |--------------------------------------------------------------------------
    */

    'presets' => [

        'formal' => [
            'name' => 'ترحيب رسمي',
            'icon' => 'fas fa-briefcase',
            'student_subject' => 'مرحباً بك في {center_name} - بيانات الدخول',
            'student_body' => 'يسعدنا إعلامك بأنه تم تسجيلك بنجاح في {center_name}.

بيانات الدخول الخاصة بك:
• رابط المنصة: {login_link}
• اسم المستخدم: {phone}
• كلمة المرور: {password}

يرجى تغيير كلمة المرور عند أول تسجيل دخول لضمان أمان حسابك.

نتمنى لك رحلة تعليمية ناجحة ومثمرة.',
            'guardian_subject' => 'تم تسجيل {student_name} في {center_name}',
            'guardian_body' => 'نود إبلاغكم بأنه تم تسجيل الطالب/ة {student_name} بنجاح في {center_name}.

stage الدراسية: {stage}

بيانات دخول الطالب/ة:
• رابط المنصة: {login_link}
• اسم المستخدم: {phone}
• كلمة المرور: {password}

يرجى الاحتفاظ بهذه البيانات في مكان آمن. سيُطلب من الطالب/ة تغيير كلمة المرور عند أول دخول.

لأي استفسار، لا تترددوا في التواصل معنا.',
        ],

        'friendly' => [
            'name' => 'ترحيب ودي',
            'icon' => 'fas fa-heart',
            'student_subject' => '🎉 أهلاً وسهلاً بك في {center_name}!',
            'student_body' => 'أهلاً {student_name}! 👋

يسعدنا انضمامك لعائلة {center_name}! 🎓

إليك بيانات دخولك للمنصة:
🔗 الرابط: {login_link}
📱 اسم المستخدم: {phone}
🔑 كلمة المرور: {password}

💡 نصيحة: غيّر كلمة المرور عند أول دخول لحماية حسابك.

نتطلع لرؤيتك قريباً! 🚀',
            'guardian_subject' => '🎓 تم تسجيل {student_name} بنجاح!',
            'guardian_body' => 'مرحباً {parent_name}! 👋

يسعدنا إخبارك بأن {student_name} أصبح جزءاً من عائلة {center_name}! 🎉

stage: {stage}

بيانات دخول الطالب/ة:
🔗 {login_link}
📱 المستخدم: {phone}
🔑 كلمة المرور: {password}

نحن هنا دائماً لمساعدتكم! 💚',
        ],

        'minimal' => [
            'name' => 'إشعار بسيط',
            'icon' => 'fas fa-file-alt',
            'student_subject' => 'بيانات الدخول - {center_name}',
            'student_body' => 'تم تسجيلك في {center_name}.

بيانات الدخول:
الرابط: {login_link}
المستخدم: {phone}
كلمة المرور: {password}

غيّر كلمة المرور عند أول دخول.',
            'guardian_subject' => 'تسجيل {student_name} - {center_name}',
            'guardian_body' => 'تم تسجيل {student_name} في {center_name}.
stage: {stage}

بيانات الدخول:
الرابط: {login_link}
المستخدم: {phone}
كلمة المرور: {password}',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Template (used when tenant has no custom settings)
    |--------------------------------------------------------------------------
    */
    'default_preset' => 'formal',
];
