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
                'formal' => "نحيطكم علماً بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\nيرجى التكرم بالسداد في الموعد المحدد لضمان استمرارية الخدمة التعليمية دون انقطاع.\nشاكرين لكم حسن تعاونكم.\n{اسم_المركز}",
                'friendly' => "أهلاً بكم في {اسم_المركز}،\nنود تذكيركم بأن موعد سداد مصروفات {اسم_الطالب} هو {تاريخ_الاستحقاق} (المبلغ: {المبلغ}).\nنسعد دائماً بوجودكم معنا ونتمنى للطالب دوام التوفيق.\nمع تحيات إدارة {اسم_المركز}",
                'urgent' => "تنبيه هام:\nنود إبلاغكم بأن مصروفات {اسم_الطالب} بقيمة {المبلغ} قد استحقت بالفعل بتاريخ {تاريخ_الاستحقاق}.\nيرجى سرعة السداد لتجنب توقف الحساب أو الخدمات.\nإذا كنتم قد سددتم بالفعل، يرجى تجاهل هذه الرسالة.\n{اسم_المركز}"
            ],
            'whatsapp' => [
                'formal' => "تذكير رسمي: مصروفات {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}. يرجى السداد لضمان استمرار الخدمة. {اسم_المركز}",
                'friendly' => "أهلاً بك! نود تذكيرك بموعد سداد مصروفات {اسم_الطالب} بتاريخ {تاريخ_الاستحقاق}. نتمنى لكم يوماً سعيداً! 🌸 {اسم_المركز}",
                'urgent' => "تنبيه عاجل: مصروفات {اسم_الطالب} مستحقة منذ {تاريخ_الاستحقاق}. يرجى السداد في أقرب وقت لتجنب انقطاع الخدمة. شكراً لك. {اسم_المركز}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'مرحباً بك في {اسم_المركز} - بيانات الدخول',
                'student_body' => "يسعدنا إعلامك بأنه تم تسجيلك بنجاح في {اسم_المركز}.\n\nبيانات الدخول الخاصة بك:\n• رابط المنصة: {رابط_الدخول}\n• اسم المستخدم: {رقم_الهاتف}\n• كلمة المرور: {كلمة_المرور}\n\nيرجى تغيير كلمة المرور عند أول تسجيل دخول لضمان أمان حسابك.\n\nنتمنى لك رحلة تعليمية ناجحة ومثمرة.",
                'guardian_subject' => 'تم تسجيل {اسم_الطالب} في {اسم_المركز}',
                'guardian_body' => "نود إبلاغكم بأنه تم تسجيل الطالب/ة {اسم_الطالب} بنجاح في {اسم_المركز}.\n\nالمرحلة الدراسية: {المرحلة}\n\nبيانات دخول الطالب/ة:\n• رابط المنصة: {رابط_الدخول}\n• اسم المستخدم: {رقم_الهاتف}\n• كلمة المرور: {كلمة_المرور}\n\nيرجى الاحتفاظ بهذه البيانات في مكان آمن. سيُطلب من الطالب/ة تغيير كلمة المرور عند أول دخول.\n\nلأي استفسار، لا تترددوا في التواصل معنا."
            ]
        ]
    ]
];

$frPresets = [
    'reminders' => [
        'presets_data' => [
            'email' => [
                'formal' => "Nous vous informons que les frais pour l'étudiant(e) {اسم_الطالب} d'un montant de {المبلغ} sont dus le {تاريخ_الاستحقاق}.\nVeuillez effectuer le paiement à temps pour assurer la continuité du service éducatif sans interruption.\nMerci de votre coopération.\n{اسم_المركز}",
                'friendly' => "Bienvenue chez {اسم_المركز},\nNous vous rappelons que la date de paiement des frais de {اسم_الطالب} est le {تاريخ_الاستحقاق} (Montant : {المبلغ}).\nNous sommes ravis de vous avoir parmi nous.\nCordialement, l'administration de {اسم_المركز}",
                'urgent' => "ALERTE IMPORTANTE :\nNous vous informons que les frais de {اسم_الطالب} d'un montant de {المبلغ} sont déjà échus depuis le {تاريخ_الاستحقاق}.\nVeuillez régulariser la situation rapidement pour éviter toute interruption de service.\n{اسم_المركز}"
            ],
            'whatsapp' => [
                'formal' => "Rappel officiel : Les frais de {اسم_الطالب} ({المبلغ}) sont dus le {تاريخ_الاستحقاق}. Merci de régulariser. {اسم_المركز}",
                'friendly' => "Bonjour ! Nous vous rappelons le paiement des frais de {اسم_الطالب} pour le {تاريخ_الاستحقاق}. Bonne journée ! 🌸 {اسم_المركز}",
                'urgent' => "ALERTE : Les frais de {اسم_الطالب} sont en retard depuis {تاريخ_الاستحقاق}. Merci de payer dès que possible. {اسم_المركز}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Bienvenue chez {اسم_المركز} - Informations de connexion',
                'student_body' => "Nous sommes heureux de vous informer que vous avez été inscrit avec succès chez {اسم_المركز}.\n\nVos informations de connexion :\n• Lien de la plateforme : {رابط_الدخول}\n• Nom d'utilisateur : {رقم_الهاتف}\n• Mot de passe : {كلمة_المرور}\n\nVeuillez changer votre mot de passe lors de votre première connexion pour assurer la sécurité de votre compte.\n\nNous vous souhaitons une expérience d'apprentissage fructueuse.",
                'guardian_subject' => 'Inscription de {اسم_الطالب} chez {اسم_المركز}',
                'guardian_body' => "Nous souhaitons vous informer que l'étudiant(e) {اسم_الطالب} a été inscrit(e) avec succès chez {اسم_المركز}.\n\nNiveau académique : {المرحلة}\n\nInformations de connexion de l'étudiant(e) :\n• Lien de la plateforme : {رابط_الدخول}\n• Nom d'utilisateur : {رقم_الهاتف}\n• Mot de passe : {كلمة_المرور}\n\nVeuillez conserver ces informations en lieu sûr. L'étudiant devra changer son mot de passe lors de sa première connexion.\n\nPour toute question, n'hésitez pas à nous contacter."
            ]
        ]
    ]
];

$enPresets = [
    'reminders' => [
        'presets_data' => [
            'email' => [
                'formal' => "We inform you that the fees for the student {اسم_الطالب} in the amount of {المبلغ} are due on {تاريخ_الاستحقاق}.\nPlease make the payment on time to ensure the continuity of the educational service without interruption.\nThank you for your cooperation.\n{اسم_المركز}",
                'friendly' => "Welcome to {اسم_المركز},\nWe remind you that the payment due date for {اسم_الطالب} is {تاريخ_الاستحقاق} (Amount: {المبلغ}).\nWe are happy to have you with us.\nBest regards, {اسم_المركز} management",
                'urgent' => "IMPORTANT ALERT:\nWe inform you that the fees for {اسم_الطالب} in the amount of {المبلغ} are already overdue since {تاريخ_الاستحقاق}.\nPlease settle the payment quickly to avoid any service interruption.\n{اسم_المركز}"
            ],
            'whatsapp' => [
                'formal' => "Official Reminder: Fees for {اسم_الطالب} ({المبلغ}) are due on {تاريخ_الاستحقاق}. Please settle. {اسم_المركز}",
                'friendly' => "Hello! We remind you of the payment due date for {اسم_الطالب} on {تاريخ_الاستحقاق}. Have a great day! 🌸 {اسم_المركز}",
                'urgent' => "URGENT ALERT: Fees for {اسم_الطالب} are overdue since {تاريخ_الاستحقاق}. Please pay as soon as possible. {اسم_المركز}"
            ]
        ]
    ],
    'email_templates' => [
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Welcome to {اسم_المركز} - Login Details',
                'student_body' => "We are pleased to inform you that you have been successfully registered at {اسم_المركز}.\n\nYour login details:\n• Platform Link: {رابط_الدخول}\n• Username: {رقم_الهاتف}\n• Password: {كلمة_المرور}\n\nPlease change your password upon first login to ensure account security.\n\nWe wish you a successful educational journey.",
                'guardian_subject' => '{اسم_الطالب} Registered at {اسم_المركز}',
                'guardian_body' => "We wish to inform you that the student {اسم_الطالب} has been successfully registered at {اسم_المركز}.\n\nAcademic Stage: {المرحلة}\n\nStudent Login Details:\n• Platform Link: {رابط_الدخول}\n• Username: {رقم_الهاتف}\n• Password: {كلمة_المرور}\n\nPlease keep these details safe. The student will be asked to change the password upon first login.\n\nFor any inquiries, feel free to contact us."
            ]
        ]
    ]
];

updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/ar/settings.php", $arPresets);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/fr/settings.php", $frPresets);
updateLangFile("d:/new project/antigravty/edu/edu/Modules/Center/resources/lang/en/settings.php", $enPresets);

echo "Localized presets added successfully!";
