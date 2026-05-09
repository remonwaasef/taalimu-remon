<?php
$basePath = __DIR__.'/Modules/Instructor/lang';

$translations = [
    'ar' => [
        'billing' => [
            'page_title' => 'إدارة الحسابات والمدفوعات',
            'student_count' => 'طالب',
            'search_placeholder' => 'بحث باسم الطالب أو رقم الهاتف...',
            'all_students' => 'كل الطلاب',
            'has_balance' => 'عليهم متبقي',
            'fully_paid' => 'تم التحصيل بالكامل',
            'no_students_registered' => 'لا يوجد طلاب مسجلين حالياً',
            'no_search_results' => 'لا توجد نتائج تطابق بحثك',
            'collected' => 'تم التحصيل',
            'cancel' => 'إلغاء',
            'current_balance' => 'المبلغ المتبقي حالياً: ',
        ],
        'students' => [
            'name_placeholder' => 'مثال: أحمد محمد علي',
            'whatsapp_bulk_alert' => 'سيتم فتح الطالب الأول. للمراسلة الجماعية الاحترافية، يوصى بربط خدمة WhatsApp API.',
            'transfer_student_prefix' => 'نقل الطالب: ',
            'student_count' => 'طالب',
        ],
        'schedules' => [
            'search_placeholder' => 'بحث باسم المجموعة (مثلاً: فيزياء)...',
            'session_word' => 'حصة',
            'capacity' => 'السعة',
            'no_matching_sessions' => 'لا توجد حصص مطابقة للبحث.',
            'total_sessions' => 'حصة إجمالاً',
        ]
    ],
    'en' => [
        'billing' => [
            'page_title' => 'Billing and Payments Management',
            'student_count' => 'Student(s)',
            'search_placeholder' => 'Search by student name or phone number...',
            'all_students' => 'All Students',
            'has_balance' => 'Has Balance',
            'fully_paid' => 'Fully Paid',
            'no_students_registered' => 'No students registered currently',
            'no_search_results' => 'No results matching your search',
            'collected' => 'Collected',
            'cancel' => 'Cancel',
            'current_balance' => 'Current remaining balance: ',
        ],
        'students' => [
            'name_placeholder' => 'e.g. Ahmed Mohamed Ali',
            'whatsapp_bulk_alert' => 'The first student will be opened. For professional bulk messaging, it is recommended to integrate WhatsApp API.',
            'transfer_student_prefix' => 'Transfer Student: ',
            'student_count' => 'Student(s)',
        ],
        'schedules' => [
            'search_placeholder' => 'Search by group name (e.g., Physics)...',
            'session_word' => 'Session',
            'capacity' => 'Capacity',
            'no_matching_sessions' => 'No sessions matching your search.',
            'total_sessions' => 'Total Sessions',
        ]
    ],
    'fr' => [
        'billing' => [
            'page_title' => 'Gestion de la facturation et des paiements',
            'student_count' => 'Étudiant(s)',
            'search_placeholder' => 'Rechercher par nom ou numéro de téléphone...',
            'all_students' => 'Tous les étudiants',
            'has_balance' => 'À Payer',
            'fully_paid' => 'Entièrement payé',
            'no_students_registered' => 'Aucun étudiant inscrit actuellement',
            'no_search_results' => 'Aucun résultat ne correspond à votre recherche',
            'collected' => 'Encaissé',
            'cancel' => 'Annuler',
            'current_balance' => 'Solde restant actuel: ',
        ],
        'students' => [
            'name_placeholder' => 'ex. Ahmed Mohamed Ali',
            'whatsapp_bulk_alert' => 'Le premier étudiant sera ouvert. Pour la messagerie de masse professionnelle, il est recommandé d\'intégrer l\'API WhatsApp.',
            'transfer_student_prefix' => 'Transférer l\'étudiant: ',
            'student_count' => 'Étudiant(s)',
        ],
        'schedules' => [
            'search_placeholder' => 'Rechercher par nom de groupe...',
            'session_word' => 'Session',
            'capacity' => 'Capacité',
            'no_matching_sessions' => 'Aucune session ne correspond à votre recherche.',
            'total_sessions' => 'Total des sessions',
        ]
    ]
];

foreach ($translations as $lang => $files) {
    foreach ($files as $file => $newKeys) {
        $filePath = "$basePath/$lang/$file.php";
        
        $existing = [];
        if (file_exists($filePath)) {
            $existing = include $filePath;
        } else {
            if(!is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
        }
        
        $merged = array_merge($existing, $newKeys);
        
        $content = "<?php\n\nreturn " . var_export($merged, true) . ";\n";
        
        file_put_contents($filePath, $content);
    }
}

echo "Translations injected successfully.\n";
