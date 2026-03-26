<?php

return [
    'title' => 'Center Setup | Educational Platform',
    'welcome_title' => 'Welcome to your new educational platform 🚀',
    'welcome_subtitle' => 'Let\'s get your center ready to launch in just 4 simple steps.',
    'security_note' => 'Your center information and data are fully encrypted and secure',
    
    'steps' => [
        'step_1' => 'Core Settings',
        'step_2' => 'Instructors',
        'step_3' => 'Courses & Subjects',
        'step_4' => 'Students',
    ],
    
    'step_1' => [
        'title' => 'Core Settings',
        'subtitle' => 'Let\'s start by configuring your platform\'s display options.',
        'language_label' => 'Default System Language',
        'currency_label' => 'Local Currency',
        'btn_submit' => 'Save & Continue',
    ],
    
    'step_2' => [
        'title' => 'Add First Instructor',
        'subtitle' => 'Add the first instructor to your platform to start linking courses.',
        'name_label' => 'Instructor Name',
        'name_placeholder' => 'e.g., Mr. John Doe',
        'specialization_label' => 'Specialization',
        'specialization_placeholder' => 'e.g., Mathematics, Science',
        'email_label' => 'Email Address (Optional)',
        'email_placeholder' => 'instructor@example.com',
        'phone_label' => 'Phone Number',
        'phone_hint' => '(Will be used for login)',
        'btn_skip' => 'Skip this step for now',
        'btn_submit' => 'Add & Continue',
    ],

    'step_3' => [
        'title' => 'Create Courses',
        'subtitle' => 'Add your first educational subject or training course.',
        'name_label' => 'Subject / Course Name',
        'name_placeholder' => 'e.g., Mathematics - Grade 10',
        'price_label' => 'Course Price',
        'sessions_label' => 'Total Sessions',
        'schedule_section' => 'Schedules (Main Appointment)',
        'day_label' => 'Day',
        'time_label' => 'Start Time',
        'time_end_label' => 'End Time',
        'btn_add_schedule' => 'Add Another Appointment',
        'btn_remove_schedule' => 'Remove',
        'btn_skip' => 'Skip for now',
        'btn_submit' => 'Create Course',
    ],

    'step_4' => [
        'title' => 'Final Step: First Student 🎉',
        'subtitle' => 'Register the first student in your platform to start interacting.',
        'name_label' => 'Student Name',
        'name_placeholder' => 'e.g., Alex Smith',
        'phone_label' => 'Student Phone Number',
        'enroll_checkbox' => 'Enroll the student in the course you created ( :course )',
        'btn_skip' => 'Skip & Go to Dashboard',
        'btn_submit' => 'Finish Setup & Start!',
    ],

    'loading' => 'Processing data...',
    'error_title' => 'Oops',
    'error_fallback' => 'An unexpected error occurred',
    'btn_ok' => 'OK',
    'currencies' => [
        'egp' => 'Egyptian Pound (EGP)',
        'sar' => 'Saudi Riyal (SAR)',
        'aed' => 'UAE Dirham (AED)',
        'usd' => 'US Dollar (USD)',
        'eur' => 'Euro (EUR)',
    ],
    'btn_back' => 'Back',
    'days' => [
        '0' => 'Sunday',
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
    ],
];
