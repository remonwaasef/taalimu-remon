<?php
$files = [
    'analytics.php' => [
        'commission_log' => 'Journal des commissions',
        'profit_loss_report' => 'Rapport des pertes et profits',
        'yearly_profit_loss' => 'Résumé annuel des profits et pertes',
        'monthly_breakdown' => 'Répartition mensuelle',
        'operating_expenses' => 'Dépenses d\'exploitation',
        'instructor_commissions' => 'Commissions des instructeurs',
        'net_result' => 'Résultat net',
        'month' => 'Mois',
        'profit' => 'Profit',
        'loss' => 'Perte',
    ],
    'courses.php' => [
        'validation_schedules_count_mismatch' => 'Le nombre d\'horaires doit être égal au nombre de séances (:count).',
        'schedules_count_info' => 'Vous devez ajouter :required horaire(s) (actuellement :current)',
        'schedules_count_complete' => 'Tous les horaires requis ont été ajoutés ✓',
    ],
    'messages.php' => [
        'total_expenses' => 'Dépenses totales',
        'net_profit' => 'Bénéfice net',
        'blade_1078' => 'Enregistrer la présence/l\'absence',
        'blade_1079' => 'Suivi de la présence de vos étudiants',
    ],
    'sales.php' => [
        'status_active' => 'Actif',
        'status_new_pending' => 'Nouveau (En attente)',
        'status_inactive_expired' => 'Inactif / Expiré',
        'saving' => 'Enregistrement en cours...',
        'sale_recorded_success' => 'Vente enregistrée avec succès',
        'cart_help' => 'Cochez la case pour sélectionner. Ajoutez le montant payé et toute remise éventuelle pour chaque sélection.',
    ],
    'schedules.php' => [
        'day' => 'Jour',
        'from' => 'De',
        'to' => 'À',
        'choose_classroom' => 'Choisir une salle',
        'internal_conflict' => 'Conflit: l\'horaire du jour :day de :from à :to chevauche un autre de vos horaires.',
        'conflict_error' => 'Il y a un conflit avec cet horaire (Même salle, ou même instructeur, ou même jour/heure).',
        'incomplete_schedules' => 'Horaires incomplets',
    ],
    'students.php' => [
        'student_details' => 'Détails de l\'étudiant',
        'status_finance' => 'Finance de l\'étudiant',
    ],
];

foreach ($files as $file => $translations) {
    $path = "Modules/Center/resources/lang/fr/" . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Remove trailing '];' and any whitespace
        $content = preg_replace('/\];\s*$/', '', $content);
        
        // Append the new translations
        foreach ($translations as $key => $value) {
            $content .= "\n    '{$key}' => '{$value}',";
        }
        
        // Add back the '];'
        $content .= "\n];\n";
        
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
