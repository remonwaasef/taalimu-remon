<?php

return [
    'title' => 'General Settings',
    'tabs' => [
        'general' => 'General',
        'academic' => 'Academic',
        'financial' => 'Financial',
        'appearance' => 'Appearance',
        'whatsapp' => 'WhatsApp',
        'email_templates' => 'Email Templates',
        'reminders' => 'Reminder Schedule',
        'privacy' => 'Privacy',
    ],
    'general' => [
        'logo' => 'Center Logo',
        'favicon' => 'Browser Favicon',
        'name' => 'Center Name',
        'phone' => 'Phone Number',
        'email' => 'Email Address',
        'address' => 'Address',
        'description' => 'Description',
        'timezone' => 'Local Timezone',
        'social_links' => 'Social Links',
        'facebook' => 'Facebook Link',
        'instagram' => 'Instagram Link',
        'twitter' => 'Twitter Link',
        'youtube' => 'YouTube Link',
        'save' => 'Save Changes',
    ],
    'academic' => [
        'year_grading' => 'Academic Year & Grading',
        'current_year' => 'Current Academic Year',
        'grading_system' => 'Grading System',
        'percentage' => 'Percentage (0-100)',
        'gpa' => 'GPA',
        'attendance_alert' => 'Automatically notify parents about student absence',
        'templates_title' => 'Save Time? Try Templates',
        'templates_desc' => 'Choose a pre-defined educational system (e.g., Egyptian System) to instantly populate stages and grades.',
        'select_template' => 'Select a template...',
        'apply' => 'Apply',
        'structure_title' => 'Academic Structure',
        'add_stage' => 'Add Stage',
        'stage_name_placeholder' => 'Stage Name (e.g., Primary)',
        'add_grade' => 'Add Grade',
        'remove_stage' => 'Remove Stage',
        'grade_name_placeholder' => 'Grade Name',
        'save_structure' => 'Save Academic Structure',
        'confirm_template' => 'Warning: Applying this template will clear your current academic structure. Do you want to proceed?',
        'select_template_first' => 'Please select a template first',
        'confirm_delete_stage' => 'Are you sure you want to delete this stage and all its grades?',
        'tech_error' => 'Sorry, a technical error occurred. Please refresh the page and try again.',
        'attendance_rules' => 'Attendance & Lateness Rules',
        'late_levels' => 'Lateness Levels',
        'late_levels_help' => 'You can define different levels of lateness. The system will compare the scan time with the session start time.',
        'threshold_minutes' => 'Minutes (after start)',
        'level_label' => 'Status Name (e.g., Mild Delay)',
        'add_level' => 'Add Lateness Level',
        'confirm_delete_level' => 'Are you sure you want to delete this lateness level?',
    ],
    'financial' => [
        'title' => 'Billing & Currency Settings',
        'currency' => 'Default Currency',
        'tax_rate' => 'Tax Rate (%)',
        'invoice_prefix' => 'Invoice Prefix',
        'currencies' => [
            'egp' => 'Egyptian Pound (EGP)',
            'sar' => 'Saudi Riyal (SAR)',
            'usd' => 'US Dollar (USD)',
            'eur' => 'Euro (EUR)',
        ],
    ],
    'appearance' => [
        'title' => 'Appearance Settings',
        'primary_color' => 'Primary System Color',
        'dark_mode' => 'Enable Dark Mode',
    ],
    'whatsapp' => [
        'title' => 'WhatsApp Integration (UltraMsg)',
        'desc' => 'Enable automatic notifications for parents and students.',
        'enabled' => 'Enable WhatsApp Service',
        'instance_id' => 'Instance ID',
        'instance_id_placeholder' => 'e.g.: instance12345',
        'token' => 'Token (API Key)',
        'info_title' => 'What messages will be sent?',
        'attendance_msg' => 'Attendance Notification: When a student is marked as present, parents receive: \\"Student [Name) arrived...\\".',
        'default_country_code' => 'Default Country Code (for local numbers)',
        'default_country_code_help' => 'Used when sending WhatsApp messages to local numbers starting with 0',
        'countries' => [
            'eg' => 'Egypt',
            'sa' => 'Saudi Arabia',
            'ae' => 'UAE',
            'kw' => 'Kuwait',
            'qa' => 'Qatar',
            'bh' => 'Bahrain',
            'om' => 'Oman',
            'jo' => 'Jordan',
            'lb' => 'Lebanon',
            'iq' => 'Iraq',
            'ly' => 'Libya',
            'tn' => 'Tunisia',
            'dz' => 'Algeria',
            'ma' => 'Morocco',
            'sd' => 'Sudan',
            'fr' => 'France',
            'gb' => 'United Kingdom',
            'us' => 'USA',
            'tr' => 'Turkey',
        ],
        'payment_msg' => 'Payment Notification: When a payment is received: \\"Received [Amount)... Remaining [Balance)\\".',
        'official_note' => 'Official Note',
    ],
    'privacy' => [
        'title' => 'Data Control Center (GDPR)',
        'desc' => 'These settings allow you to exercise your rights to access or delete your personal data (Right to be Forgotten).',
        'export_title' => 'Export My Data',
        'export_desc' => 'Download a complete copy of your data (Profile, Exams, Records) in JSON format.',
        'export_btn' => 'Download Data',
        'delete_title' => 'Delete Account Permanently (Danger Zone)',
        'delete_desc' => 'This action will permanently delete all your data and anonymize your identity from public records. This cannot be undone.',
        'delete_btn' => 'Delete My Account',
        'confirm_delete_desc' => 'For security, please enter your password to confirm deletion.',
        'current_password' => 'Current Password',
        'understand_checkbox' => 'I understand that this action is permanent and data cannot be recovered.',
        'cancel' => 'Cancel',
        'delete_perm' => 'Delete Permanently',
        'confirm_delete_title' => 'Confirm Delete Title',
    ],
    'email_templates' => [
        'title' => 'Email Settings',
        'desc' => 'Control the automatic welcome messages sent when a new student is registered.',
        'choose_preset' => 'Choose a Preset',
        'student_welcome' => 'Student Welcome Email',
        'guardian_welcome' => 'Guardian Welcome Email',
        'to_default' => 'Reset to Default',
        'activate' => 'Enable',
        'subject' => 'Message Subject',
        'body' => 'Message Body',
        'placeholders' => [
            'student_name' => 'Student Name',
            'center_name' => 'Center Name',
            'login_link' => 'Login Link',
            'password' => 'Password',
            'phone' => 'Phone Number',
            'parent_name' => 'Parent Name',
            'stage' => 'Academic Stage',
            'amount' => 'Amount',
            'due_date' => 'Due Date',
            'group_name' => 'Group Name',
            'course_price' => 'Course Price',
            'amount_paid' => 'Amount Paid',
            'payment_date' => 'Payment Date',
            'remaining' => 'Remaining',
            'payment_method' => 'Payment Method',
            'course_name' => 'Session/Course Name',
            'status' => 'Attendance Status',
            'date' => 'Date',
        ],
        'save' => 'Save Email Settings',
        'reset' => 'Reset to Defaults',
        'confirm_reset' => 'Are you sure you want to clear all customizations and reset to default texts?',
        'preview_title' => 'Message Preview (Now)',
        'preview_help' => 'Placeholders appear as sample data in the preview for demonstration purposes only.',
        'pro_tip' => 'Pro Tip',
        'pro_tip_desc' => 'Use automatic placeholders to make your messages more personal. Messages starting with the student\'s name achieve 40% higher engagement!',
        'notif_title' => 'Automatic Email Notifications',
        'notif_desc' => 'Enable or disable automatic emails for specific events. You can customize the text for each message.',
        'payment_reminder' => 'Payment Reminder',
        'payment_reminder_desc' => 'Sent to student or guardian before the payment due date',
        'group_enrollment' => 'New Group Enrollment',
        'group_enrollment_desc' => 'Sent when a student is added to a group or course',
        'payment_confirmation' => 'Payment Confirmation',
        'payment_confirmation_desc' => 'Sent when a new payment is recorded for the student',
        'attendance_notif' => 'Attendance Notification',
        'attendance_notif_desc' => 'Sent to student or guardian immediately upon recording attendance',
        'notif_status' => 'Notification Status',
        'notif_active' => 'Active',
        'presets' => [
            'formal' => 'Formal Welcome',
            'friendly' => 'Friendly Welcome',
            'minimal' => 'Simple Notification',
        ],
        'defaults' => [
            'payment_reminder_subject' => 'Payment Reminder for {student_name} - {center_name}',
            'payment_reminder_body' => 'We remind you that the fees for student {student_name} in the amount of {amount} are due on {due_date}.

Please make the payment on time to ensure continued service.

Thank you for your cooperation,
{center_name}',
            'group_enrollment_subject' => 'You have been registered in a new group - {center_name}',
            'group_enrollment_body' => 'Hello {student_name},

You have been registered in a new group: {group_name}

You can access the platform through:
{login_link}

We wish you success!
{center_name}',
            'payment_confirmation_subject' => 'Payment Receipt Confirmation - {center_name}',
            'payment_confirmation_body' => 'Hello {student_name},

We confirm receipt of a payment with the following details:
• Amount: {paid_amount}
• Date: {payment_date}
• Payment Method: {payment_method}
• Remaining: {remaining}

Thank you for your commitment.
{center_name}',
        ],
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Welcome to {center_name} - Login Details',
                'student_body' => 'We are pleased to inform you that you have been successfully registered at {center_name}.

Your login details:
• Platform Link: {login_link}
• Username: {phone}
• Password: {password}

Please change your password upon first login to ensure account security.

We wish you a successful educational journey.',
                'guardian_subject' => '{student_name} Registered at {center_name}',
                'guardian_body' => 'We wish to inform you that the student {student_name} has been successfully registered at {center_name}.

Academic Stage: {stage}

Student Login Details:
• Platform Link: {login_link}
• Username: {phone}
• Password: {password}

Please keep these details safe. The student will be asked to change the password upon first login.

For any inquiries, feel free to contact us.',
            ],
        ],
    ],
    'reminders' => [
        'title' => 'Payment Reminder Schedule',
        'subtitle' => 'Set the timing for automatic payment reminder messages. You can configure up to 3 reminders before the due date and recurring reminders after the deadline until payment is made.',
        'default_settings' => 'Default Settings',
        'default_due_day' => 'Default Payment Due Day',
        'default_due_day_hint' => 'The day of each month when fees are due (1 to 28)',
        'default_monthly_fee' => 'Default Monthly Fee',
        'default_monthly_fee_hint' => 'Can be customized per student individually',
        'pre_due_title' => 'Pre-Due Reminders (Email)',
        'pre_due_desc' => 'Send email reminders before the payment due date. You can set up to 3 reminders.',
        'days_before_due' => ':days days before due',
        'on_due_day' => 'On due day',
        'post_due_title' => 'Post-Due Reminders (Email + WhatsApp)',
        'post_due_desc' => 'Send reminders after the payment due date. Continues automatically until payment is made.',
        'days_after_due' => ':days days after due',
        'whatsapp_before_due' => 'Also send WhatsApp before due date',
        'whatsapp_before_due_warning' => '⚠️ Sending WhatsApp before due costs extra messages. Enable only when needed.',
        'overdue_auto_title' => 'Automatic Overdue Reminders',
        'overdue_auto_desc' => 'Send automatic reminders every specified number of days after the deadline until payment is made.',
        'overdue_repeat_enabled' => 'Enable recurring overdue reminders',
        'overdue_repeat_interval' => 'Repeat every (days)',
        'overdue_repeat_interval_hint' => 'Example: every 7 days = weekly recurring reminder',
        'overdue_max_reminders' => 'Maximum number of reminders',
        'overdue_max_reminders_hint' => 'Leave empty to continue until payment',
        'save_settings' => 'Save Reminder Settings',
        'saved' => 'Reminder settings saved successfully',
        'template_variables' => 'Available variables:',
        'email_template' => 'Email Message Template',
        'whatsapp_template' => 'WhatsApp Message Template',
        'info_banner' => 'The system runs automatically daily. When a new student enrolls in a group with outstanding fees, the system starts sending reminders according to the schedule set here.',
        'channels_title' => 'Reminder Channels',
        'channel_email' => 'Email (Free)',
        'channel_whatsapp' => 'WhatsApp (Requires setup)',
        'channel_both' => 'Both',
        'sub_tabs' => [
            'welcome' => 'Welcome Messages',
            'system' => 'System Notifications',
            'payment' => 'Payment Reminders',
        ],
        'quick_templates_email' => 'Quick Email Templates:',
        'quick_templates_whatsapp' => 'Quick WhatsApp Templates:',
        'presets' => [
            'formal' => 'Formal',
            'friendly' => 'Friendly',
            'urgent' => 'Urgent',
        ],
        'presets_data' => [
            'email' => [
                'formal' => 'We inform you that the fees for the student {student_name} in the amount of {amount} are due on {due_date}.
Please make the payment on time to ensure the continuity of the educational service without interruption.
Thank you for your cooperation.
{center_name}',
                'friendly' => 'Welcome to {center_name},
We remind you that the payment due date for {student_name} is {due_date} (Amount: {amount}).
We are happy to have you with us.
Best regards, {center_name} management',
                'urgent' => 'IMPORTANT ALERT:
We inform you that the fees for {student_name} in the amount of {amount} are already overdue since {due_date}.
Please settle the payment quickly to avoid any service interruption.
{center_name}',
            ],
            'whatsapp' => [
                'formal' => 'Official Reminder: Fees for {student_name} ({amount}) are due on {due_date}. Please settle. {center_name}',
                'friendly' => 'Hello! We remind you of the payment due date for {student_name} on {due_date}. Have a great day! 🌸 {center_name}',
                'urgent' => 'URGENT ALERT: Fees for {student_name} are overdue since {due_date}. Please pay as soon as possible. {center_name}',
            ],
        ],
        'timeline_preview' => 'Timeline Preview',
        'timeline_desc' => 'Visualization of automatic reminders.',
        'whatsapp_placeholder' => 'Reminder: Student {student_name}\'s fees ({amount}) are due on {due_date}. Please pay. {center_name}',
    ],
    'general_timezone_help' => 'Affects the date and time display in reports',
    'academic_system_defaults_alert' => 'System default settings are currently being used. Custom settings will be saved once you click Save.',
    'academic_restore_defaults' => 'Restore Defaults',
    'updated' => 'Settings updated successfully',
];
