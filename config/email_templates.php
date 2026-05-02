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
            'name_en' => 'Formal Welcome',
            'name_fr' => 'Bienvenue Formelle',
            'icon' => 'fas fa-briefcase',

            // Arabic (default)
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

            // English
            'student_subject_en' => 'Welcome to {center_name} - Login Details',
            'student_body_en' => 'We are pleased to inform you that you have been successfully registered at {center_name}.

Your login credentials:
• Platform link: {login_link}
• Username: {phone}
• Password: {password}

Please change your password upon first login to secure your account.

We wish you a successful learning journey.',
            'guardian_subject_en' => '{student_name} has been registered at {center_name}',
            'guardian_body_en' => 'We would like to inform you that {student_name} has been successfully registered at {center_name}.

Grade Level: {stage}

Student login credentials:
• Platform link: {login_link}
• Username: {phone}
• Password: {password}

Please keep these credentials in a safe place. The student will be asked to change the password upon first login.

For any inquiries, please do not hesitate to contact us.',

            // French
            'student_subject_fr' => 'Bienvenue à {center_name} - Identifiants de connexion',
            'student_body_fr' => 'Nous avons le plaisir de vous informer que votre inscription à {center_name} a été effectuée avec succès.

Vos identifiants de connexion :
• Lien de la plateforme : {login_link}
• Nom d\'utilisateur : {phone}
• Mot de passe : {password}

Veuillez changer votre mot de passe lors de votre première connexion pour sécuriser votre compte.

Nous vous souhaitons un parcours éducatif réussi.',
            'guardian_subject_fr' => '{student_name} a été inscrit à {center_name}',
            'guardian_body_fr' => 'Nous souhaitons vous informer que {student_name} a été inscrit avec succès à {center_name}.

Niveau scolaire : {stage}

Identifiants de connexion de l\'élève :
• Lien de la plateforme : {login_link}
• Nom d\'utilisateur : {phone}
• Mot de passe : {password}

Veuillez conserver ces informations en lieu sûr. L\'élève sera invité à changer le mot de passe lors de sa première connexion.

Pour toute question, n\'hésitez pas à nous contacter.',
        ],

        'friendly' => [
            'name' => 'ترحيب ودي',
            'name_en' => 'Friendly Welcome',
            'name_fr' => 'Bienvenue Amicale',
            'icon' => 'fas fa-heart',

            // Arabic (default)
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

            // English
            'student_subject_en' => '🎉 Welcome to {center_name}!',
            'student_body_en' => 'Hello {student_name}! 👋

We\'re thrilled to have you join the {center_name} family! 🎓

Here are your login credentials:
🔗 Link: {login_link}
📱 Username: {phone}
🔑 Password: {password}

💡 Tip: Change your password on first login to keep your account secure.

See you soon! 🚀',
            'guardian_subject_en' => '🎓 {student_name} has been registered successfully!',
            'guardian_body_en' => 'Hello {parent_name}! 👋

We\'re happy to let you know that {student_name} is now part of the {center_name} family! 🎉

Grade: {stage}

Student login credentials:
🔗 {login_link}
📱 Username: {phone}
🔑 Password: {password}

We\'re always here to help! 💚',

            // French
            'student_subject_fr' => '🎉 Bienvenue à {center_name} !',
            'student_body_fr' => 'Bonjour {student_name} ! 👋

Nous sommes ravis de vous accueillir dans la famille {center_name} ! 🎓

Voici vos identifiants de connexion :
🔗 Lien : {login_link}
📱 Nom d\'utilisateur : {phone}
🔑 Mot de passe : {password}

💡 Conseil : Changez votre mot de passe lors de la première connexion pour sécuriser votre compte.

À bientôt ! 🚀',
            'guardian_subject_fr' => '🎓 {student_name} a été inscrit avec succès !',
            'guardian_body_fr' => 'Bonjour {parent_name} ! 👋

Nous sommes heureux de vous informer que {student_name} fait maintenant partie de la famille {center_name} ! 🎉

Niveau : {stage}

Identifiants de connexion de l\'élève :
🔗 {login_link}
📱 Utilisateur : {phone}
🔑 Mot de passe : {password}

Nous sommes toujours là pour vous aider ! 💚',
        ],

        'minimal' => [
            'name' => 'إشعار بسيط',
            'name_en' => 'Simple Notification',
            'name_fr' => 'Notification Simple',
            'icon' => 'fas fa-file-alt',

            // Arabic (default)
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

            // English
            'student_subject_en' => 'Login Credentials - {center_name}',
            'student_body_en' => 'You have been registered at {center_name}.

Login details:
Link: {login_link}
Username: {phone}
Password: {password}

Please change your password on first login.',
            'guardian_subject_en' => '{student_name} Registration - {center_name}',
            'guardian_body_en' => '{student_name} has been registered at {center_name}.
Grade: {stage}

Login details:
Link: {login_link}
Username: {phone}
Password: {password}',

            // French
            'student_subject_fr' => 'Identifiants de connexion - {center_name}',
            'student_body_fr' => 'Vous avez été inscrit à {center_name}.

Identifiants de connexion :
Lien : {login_link}
Utilisateur : {phone}
Mot de passe : {password}

Veuillez changer votre mot de passe lors de la première connexion.',
            'guardian_subject_fr' => 'Inscription de {student_name} - {center_name}',
            'guardian_body_fr' => '{student_name} a été inscrit à {center_name}.
Niveau : {stage}

Identifiants de connexion :
Lien : {login_link}
Utilisateur : {phone}
Mot de passe : {password}',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Template (used when tenant has no custom settings)
    |--------------------------------------------------------------------------
    */
    'default_preset' => 'formal',
];
