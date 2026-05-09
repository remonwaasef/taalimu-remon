<?php

function updateLangFile($path, $newKeys) {
    if (!file_exists($path)) return;
    $content = include $path;
    $content = array_replace_recursive($content, $newKeys);
    $export = var_export($content, true);
    $export = preg_replace('/array \(/', '[', $export);
    $export = preg_replace('/\)/', ']', $export);
    $export = preg_replace('/=> \n\s+\[/', '=> [', $export);
    $export = preg_replace_callback("/'([^'\\\\]|\\\\.)*'/", function($m) {
        return str_replace(']', ')', $m[0]);
    }, $export);
    file_put_contents($path, "<?php\n\nreturn " . $export . ";\n");
}

$arPresets = [
    'reminders' => [
        'presets_data' => [
            'email' => [
                'formal' => "نحيطكم علماً بأن مصروفات الطالب/ة {student_name} بمبلغ {amount} مستحقة بتاريخ {due_date}.\nيرجى التكرم بالسداد في الموعد المحدد لضمان استمرارية الخدمة التعليمية دون انقطاع.\nشاكرين لكم حسن تعاونكم.\n{center_name}",
                'friendly' => "أهلاً بكم في {center_name}،\nنود تذكيركم بأن موعد سداد مصروفات {student_name} هو {due_date} (amount: {amount}).\nنسعد دائماً بوجودكم معنا ونتمنى للطالب دوام التوفيق.\nمع تحيات إدارة {center_name}",
                'urgent' => "تنبيه هام:\nنود إبلاغكم بأن مصروفات {student_name} بقيمة {amount} قد استحقت بالفعل بتاريخ {due_date}.\nيرجى سرعة السداد لتجنب توقف الحساب أو الخدمات.\nإذا كنتم قد سددتم بالفعل، يرجى تجاهل هذه الرسالة.\n{center_name}"
            ],
            'whatsapp' => [
                'formal' => "تذكير رسمي: مصروفات {student_name} بمبلغ {amount} مستحقة بتاريخ {due_date}. يرجى السداد لضمان استمرار الخدمة. {center_name}",
                'friendly' => "أهلاً بك! نود تذكيرك بموعد سداد مصروفات {student_name} بتاريخ {due_date}. نتمنى لكم يوماً سعيداً! 🌸 {center_name}",
                'urgent' => "تنبيه عاجل: مصروفات {student_name} مستحقة منذ {due_date}. يرجى السداد في أقرب وقت لتجنب انقطاع الخدمة. شكراً لك. {center_name}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'مرحباً بك في {center_name} - بيانات الدخول',
                'student_body' => "يسعدنا إعلامك بأنه تم تسجيلك بنجاح في {center_name}.\n\nبيانات الدخول الخاصة بك:\n• رابط المنصة: {login_link}\n• اسم المستخدم: {phone}\n• كلمة المرور: {password}\n\nيرجى تغيير كلمة المرور عند أول تسجيل دخول لضمان أمان حسابك.\n\nنتمنى لك رحلة تعليمية ناجحة ومثمرة.",
                'guardian_subject' => 'تم تسجيل {student_name} في {center_name}',
                'guardian_body' => "نود إبلاغكم بأنه تم تسجيل الطالب/ة {student_name} بنجاح في {center_name}.\n\nstage الدراسية: {stage}\n\nبيانات دخول الطالب/ة:\n• رابط المنصة: {login_link}\n• اسم المستخدم: {phone}\n• كلمة المرور: {password}\n\nيرجى الاحتفاظ بهذه البيانات في مكان آمن. سيُطلب من الطالب/ة تغيير كلمة المرور عند أول دخول.\n\nلأي استفسار، لا تترددوا في التواصل معنا."
            ]
        ]
    ]
];

$frPresets = [
    'reminders' => [
        'presets_data' => [
            'email' => [
                'formal' => "Nous vous informons que les frais pour l'étudiant(e) {student_name} d'un montant de {amount} sont dus le {due_date}.\nVeuillez effectuer le paiement à temps pour assurer la continuité du service éducatif sans interruption.\nMerci de votre coopération.\n{center_name}",
                'friendly' => "Bienvenue chez {center_name},\nNous vous rappelons que la date de paiement des frais de {student_name} est le {due_date} (Montant : {amount}).\nNous sommes ravis de vous avoir parmi nous.\nCordialement, l'administration de {center_name}",
                'urgent' => "ALERTE IMPORTANTE :\nNous vous informons que les frais de {student_name} d'un montant de {amount} sont déjà échus depuis le {due_date}.\nVeuillez régulariser la situation rapidement pour éviter toute interruption de service.\n{center_name}"
            ],
            'whatsapp' => [
                'formal' => "Rappel officiel : Les frais de {student_name} ({amount}) sont dus le {due_date}. Merci de régulariser. {center_name}",
                'friendly' => "Bonjour ! Nous vous rappelons le paiement des frais de {student_name} pour le {due_date}. Bonne journée ! 🌸 {center_name}",
                'urgent' => "ALERTE : Les frais de {student_name} sont en retard depuis {due_date}. Merci de payer dès que possible. {center_name}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Bienvenue chez {center_name} - Informations de connexion',
                'student_body' => "Nous sommes heureux de vous informer que vous avez été inscrit avec succès chez {center_name}.\n\nVos informations de connexion :\n• Lien de la plateforme : {login_link}\n• Nom d'utilisateur : {phone}\n• Mot de passe : {password}\n\nVeuillez changer votre mot de passe lors de votre première connexion pour assurer la sécurité de votre compte.\n\nNous vous souhaitons une expérience d'apprentissage fructueuse.",
                'guardian_subject' => 'Inscription de {student_name} chez {center_name}',
                'guardian_body' => "Nous souhaitons vous informer que l'étudiant(e) {student_name} a été inscrit(e) avec succès chez {center_name}.\n\nNiveau académique : {stage}\n\nInformations de connexion de l'étudiant(e) :\n• Lien de la plateforme : {login_link}\n• Nom d'utilisateur : {phone}\n• Mot de passe : {password}\n\nVeuillez conserver ces informations en lieu sûr. L'étudiant devra changer son mot de passe lors de sa première connexion.\n\nPour toute question, n'hésitez pas à nous contacter."
            ]
        ]
    ]
];

$enPresets = [
    'reminders' => [
        'presets_data' => [
            'email' => [
                'formal' => "We inform you that the fees for the student {student_name} in the amount of {amount} are due on {due_date}.\nPlease make the payment on time to ensure the continuity of the educational service without interruption.\nThank you for your cooperation.\n{center_name}",
                'friendly' => "Welcome to {center_name},\nWe remind you that the payment due date for {student_name} is {due_date} (Amount: {amount}).\nWe are happy to have you with us.\nBest regards, {center_name} management",
                'urgent' => "IMPORTANT ALERT:\nWe inform you that the fees for {student_name} in the amount of {amount} are already overdue since {due_date}.\nPlease settle the payment quickly to avoid any service interruption.\n{center_name}"
            ],
            'whatsapp' => [
                'formal' => "Official Reminder: Fees for {student_name} ({amount}) are due on {due_date}. Please settle. {center_name}",
                'friendly' => "Hello! We remind you of the payment due date for {student_name} on {due_date}. Have a great day! 🌸 {center_name}",
                'urgent' => "URGENT ALERT: Fees for {student_name} are overdue since {due_date}. Please pay as soon as possible. {center_name}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Welcome to {center_name} - Login Details',
                'student_body' => "We are pleased to inform you that you have been successfully registered at {center_name}.\n\nYour login details:\n• Platform Link: {login_link}\n• Username: {phone}\n• Password: {password}\n\nPlease change your password upon first login to ensure account security.\n\nWe wish you a successful educational journey.",
                'guardian_subject' => '{student_name} Registered at {center_name}',
                'guardian_body' => "We wish to inform you that the student {student_name} has been successfully registered at {center_name}.\n\nAcademic Stage: {stage}\n\nStudent Login Details:\n• Platform Link: {login_link}\n• Username: {phone}\n• Password: {password}\n\nPlease keep these details safe. The student will be asked to change the password upon first login.\n\nFor any inquiries, feel free to contact us."
            ]
        ]
    ]
];

updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/ar/settings.php", $arPresets);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/fr/settings.php", $frPresets);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/en/settings.php", $enPresets);

echo "Localized presets added successfully!";
