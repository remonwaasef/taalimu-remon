<?php

return [
    'title' => 'Paramètres Généraux',
    'tabs' => [
        'general' => 'Général',
        'academic' => 'Académique',
        'financial' => 'Financier',
        'appearance' => 'Apparence',
        'whatsapp' => 'WhatsApp',
        'email_templates' => 'Modèles d\'e-mail',
        'reminders' => 'Planification des Rappels',
        'privacy' => 'Confidentialité',
    ],
    'general' => [
        'logo' => 'Logo du Centre',
        'favicon' => 'Icône (Favicon)',
        'name' => 'Nom du Centre',
        'phone' => 'Numéro de Téléphone',
        'email' => 'Adresse E-mail',
        'address' => 'Adresse',
        'description' => 'Description du Centre',
        'social_links' => 'Liens de Réseaux Sociaux',
        'facebook' => 'Lien Facebook',
        'instagram' => 'Lien Instagram',
        'twitter' => 'Lien Twitter',
        'youtube' => 'Lien YouTube',
        'save' => 'Enregistrer les modifications',
        'timezone' => 'Fuseau Horaire',
    ],
    'academic' => [
        'year_grading' => 'Année Académique & Notation',
        'current_year' => 'Année Académique Actuelle',
        'grading_system' => 'Système de Notation',
        'percentage' => 'Pourcentage (0-100)',
        'gpa' => 'Moyenne Générale (GPA)',
        'attendance_alert' => 'Avertir automatiquement les parents en cas d\'absence',
        'templates_title' => 'Gagner du temps ? Essayez les modèles',
        'templates_desc' => 'Choisissez un système éducatif prédéfini (ex: Système Égyptien) pour remplir instantanément les cycles et les classes.',
        'select_template' => 'Sélectionnez un modèle...',
        'apply' => 'Appliquer',
        'structure_title' => 'Structure Académique',
        'add_stage' => 'Ajouter un cycle',
        'stage_name_placeholder' => 'Nom du cycle (ex: Primaire)',
        'add_grade' => 'Ajouter une classe',
        'remove_stage' => 'Supprimer le cycle',
        'grade_name_placeholder' => 'Nom de la classe',
        'save_structure' => 'Enregistrer la structure académique',
        'confirm_template' => 'Attention : L\'application de ce modèle supprimera votre structure actuelle. Voulez-vous continuer ?',
        'select_template_first' => 'Veuillez d\'abord sélectionner un modèle',
        'confirm_delete_stage' => 'Êtes-vous sûr de vouloir supprimer ce cycle et toutes ses classes ?',
        'tech_error' => 'Désolé, une erreur technique est survenue. Veuillez rafraîchir la page et réessayer.',
        'attendance_rules' => 'Règles de Présence et Retard',
        'late_levels' => 'Niveaux de Retard',
        'late_levels_help' => 'Définissez les niveaux de retard.',
        'threshold_minutes' => 'Minutes',
        'level_label' => 'Description (ex: Retard léger)',
        'add_level' => 'Ajouter un Niveau',
        'confirm_delete_level' => 'Êtes-vous sûr ?',
    ],
    'financial' => [
        'title' => 'Paramètres de Facturation & Devise',
        'currency' => 'Devise par Défaut',
        'tax_rate' => 'Taux de Taxe (%)',
        'invoice_prefix' => 'Préfixe de Facture',
        'currencies' => [
            'egp' => 'Livre Égyptienne (EGP)',
            'sar' => 'Riyal Saoudien (SAR)',
            'usd' => 'Dollar Américain (USD)',
            'eur' => 'Euro (EUR)',
        ],
    ],
    'appearance' => [
        'title' => 'Paramètres d\'Apparence',
        'primary_color' => 'Couleur Principale du Système',
        'dark_mode' => 'Activer le Mode Sombre',
    ],
    'whatsapp' => [
        'title' => 'Intégration WhatsApp (UltraMsg)',
        'desc' => 'Activez les notifications automatiques pour les parents et les étudiants.',
        'enabled' => 'Activer le service WhatsApp',
        'instance_id' => 'ID de l\'instance',
        'instance_id_placeholder' => 'ex: instance12345',
        'token' => 'Jeton (Clé API)',
        'info_title' => 'Quels messages seront envoyés ?',
        'attendance_msg' => 'Notification de présence : Lorsqu\'un étudiant est marqué présent, les parents reçoivent : \\"L\'étudiant [Nom) est arrivé...\\".',
        'payment_msg' => 'Notification de paiement : Lorsqu\'un paiement est reçu : "Reçu [Montant)... Reste [Solde)".',
        'default_country_code' => 'Code pays par défaut (pour les numéros locaux)',
        'default_country_code_help' => 'Utilisé lors de l\'envoi de messages WhatsApp à des numéros locaux commençant par 0',
        'countries' => [
            'eg' => 'Égypte',
            'sa' => 'Arabie Saoudite',
            'ae' => 'Émirats Arabes Unis',
            'kw' => 'Koweït',
            'qa' => 'Qatar',
            'bh' => 'Bahreïn',
            'om' => 'Oman',
            'jo' => 'Jordanie',
            'lb' => 'Liban',
            'iq' => 'Irak',
            'ly' => 'Libye',
            'tn' => 'Tunisie',
            'dz' => 'Algérie',
            'ma' => 'Maroc',
            'sd' => 'Soudan',
            'fr' => 'France',
            'gb' => 'Royaume-Uni',
            'us' => 'États-Unis',
            'tr' => 'Turquie',
        ],
        'official_note' => 'Official Note',
    ],
    'privacy' => [
        'title' => 'Centre de Contrôle des Données (RGPD)',
        'desc' => 'Ces paramètres vous permettent d\'exercer vos droits d\'accès ou de suppression de vos données personnelles (Droit à l\'oubli).',
        'export_title' => 'Exporter mes données',
        'export_desc' => 'Téléchargez une copie complète de vos données (Profil, Examens, Dossiers) au format JSON.',
        'export_btn' => 'Télécharger les données',
        'delete_title' => 'Supprimer définitivement le compte (Zone de danger)',
        'delete_desc' => 'Cette action supprimera définitivement toutes vos données et anonymisera votre identité des registres publics. Cette action est irréversible.',
        'delete_btn' => 'Supprimer mon compte',
        'confirm_delete_title' => 'Confirmer la suppression du compte',
        'confirm_delete_desc' => 'Par sécurité, veuillez saisir votre mot de passe pour confirmer la suppression.',
        'current_password' => 'Mot de passe actuel',
        'understand_checkbox' => 'Je comprends que cette action est permanente et que les données ne peuvent pas être récupérées.',
        'cancel' => 'Annuler',
        'delete_perm' => 'Supprimer définitivement',
    ],
    'email_templates' => [
        'title' => 'Paramètres de Messagerie',
        'desc' => 'Contrôlez les messages de bienvenue automatiques envoyés lorsqu\'un nouvel étudiant est enregistré.',
        'choose_preset' => 'Choisir un Modèle',
        'student_welcome' => 'E-mail de Bienvenue Étudiant',
        'guardian_welcome' => 'E-mail de Bienvenue Parent',
        'to_default' => 'Rétablir par Défaut',
        'activate' => 'Activer',
        'subject' => 'Objet du Message',
        'body' => 'Corps du Message',
        'placeholders' => [
            'student_name' => 'Nom de l\'Étudiant',
            'center_name' => 'Nom du Centre',
            'login_link' => 'Lien de Connexion',
            'password' => 'Mot de Passe',
            'phone' => 'Numéro de Téléphone',
            'parent_name' => 'Nom du Parent',
            'stage' => 'Niveau Académique',
            'amount' => 'Montant',
            'due_date' => 'Date d\'Échéance',
            'group_name' => 'Nom du Groupe',
            'course_price' => 'Prix du Cours',
            'amount_paid' => 'Montant Payé',
            'payment_date' => 'Date de Paiement',
            'remaining' => 'Reste',
            'payment_method' => 'Mode de Paiement',
            'course_name' => 'Nom de la Session/Cours',
            'status' => 'Statut de Présence',
            'date' => 'Date',
        ],
        'save' => 'Enregistrer les Paramètres',
        'reset' => 'Réinitialiser',
        'confirm_reset' => 'Êtes-vous sûr de vouloir effacer toutes les personnalisations ?',
        'preview_title' => 'Aperçu du Message',
        'preview_help' => 'Les balises apparaissent comme des données d\'exemple dans l\'aperçu.',
        'pro_tip' => 'Conseil Pro',
        'pro_tip_desc' => 'Utilisez des balises automatiques pour personnaliser vos messages. Les messages commençant par le nom de l\'étudiant augmentent l\'engagement de 40% !',
        'notif_title' => 'Notifications Automatiques par E-mail',
        'notif_desc' => 'Activez ou désactivez les e-mails automatiques pour des événements spécifiques. Vous pouvez personnaliser le texte de chaque message.',
        'payment_reminder' => 'Rappel de Paiement',
        'payment_reminder_desc' => 'Envoyé à l\'étudiant ou au parent avant la date d\'échéance',
        'group_enrollment' => 'Nouvelle Inscription au Groupe',
        'group_enrollment_desc' => 'Envoyé lorsqu\'un étudiant est ajouté à un groupe ou à un cours',
        'payment_confirmation' => 'Confirmation de Paiement',
        'payment_confirmation_desc' => 'Envoyé lorsqu\'un nouveau paiement est enregistré pour l\'étudiant',
        'attendance_notif' => 'Notification de présence',
        'attendance_notif_desc' => 'Envoyé à l\'étudiant ou au parent immédiatement après l\'enregistrement de la présence',
        'notif_status' => 'Statut de Notification',
        'notif_active' => 'Actif',
        'presets' => [
            'formal' => 'Accueil Formel',
            'friendly' => 'Accueil Amical',
            'minimal' => 'Notification Simple',
        ],
        'defaults' => [
            'payment_reminder_subject' => 'Rappel de paiement pour {student_name} - {center_name}',
            'payment_reminder_body' => 'Nous vous rappelons que les frais pour l\\\'étudiant {student_name} d\\\'un montant de {amount} sont dus le {due_date}.

Veuillez effectuer le paiement à temps pour assurer la continuité du service.

Merci de votre coopération,
{center_name}',
            'group_enrollment_subject' => 'Vous avez été inscrit dans un nouveau groupe - {center_name}',
            'group_enrollment_body' => 'Bonjour {student_name},

Vous avez été inscrit dans un nouveau groupe : {group_name}

Vous pouvez accéder à la plateforme via :
{login_link}

Nous vous souhaitons beaucoup de succès !
{center_name}',
            'payment_confirmation_subject' => 'Confirmation de réception de paiement - {center_name}',
            'payment_confirmation_body' => 'Bonjour {student_name},

Nous confirmons la réception d\\\'un paiement avec les détails suivants :
• Montant : {paid_amount}
• Date : {payment_date}
• Mode de paiement : {payment_method}
• Reste : {remaining}

Merci de votre engagement.
{center_name}',
        ],
        'presets_data' => [
            'formal' => [
                'student_subject' => 'Bienvenue chez {center_name} - Informations de connexion',
                'student_body' => 'Nous sommes heureux de vous informer que vous avez été inscrit avec succès chez {center_name}.

Vos informations de connexion :
• Lien de la plateforme : {login_link}
• Nom d\'utilisateur : {phone}
• Mot de passe : {password}

Veuillez changer votre mot de passe lors de votre première connexion pour assurer la sécurité de votre compte.

Nous vous souhaitons une expérience d\'apprentissage fructueuse.',
                'guardian_subject' => 'Inscription de {student_name} chez {center_name}',
                'guardian_body' => 'Nous souhaitons vous informer que l\'étudiant(e) {student_name} a été inscrit(e) avec succès chez {center_name}.

Niveau académique : {stage}

Informations de connexion de l\'étudiant(e) :
• Lien de la plateforme : {login_link}
• Nom d\'utilisateur : {phone}
• Mot de passe : {password}

Veuillez conserver ces informations en lieu sûr. L\'étudiant devra changer son mot de passe lors de sa première connexion.

Pour toute question, n\'hésitez pas à nous contacter.',
            ],
        ],
    ],
    'reminders' => [
        'title' => 'Planification des Rappels de Paiement',
        'subtitle' => 'Définissez les horaires d\'envoi automatique des rappels de paiement. Vous pouvez configurer jusqu\'à 3 rappels avant l\'échéance et des rappels récurrents après la date limite jusqu\'au paiement.',
        'default_settings' => 'Paramètres par Défaut',
        'default_due_day' => 'Jour d\'échéance par défaut',
        'default_due_day_hint' => 'Le jour de chaque mois où les frais sont dus (1 à 28)',
        'default_monthly_fee' => 'Frais mensuels par défaut',
        'default_monthly_fee_hint' => 'Peut être personnalisé pour chaque étudiant',
        'pre_due_title' => 'Rappels avant l\'échéance (E-mail)',
        'pre_due_desc' => 'Envoyer des rappels par e-mail avant la date d\'échéance. Vous pouvez configurer jusqu\'à 3 rappels.',
        'days_before_due' => ':days jours avant l\'échéance',
        'on_due_day' => 'Le jour de l\'échéance',
        'post_due_title' => 'Rappels après l\'échéance (E-mail + WhatsApp)',
        'post_due_desc' => 'Envoyer des rappels après la date d\'échéance. Continue automatiquement jusqu\'au paiement.',
        'days_after_due' => ':days jours après l\'échéance',
        'whatsapp_before_due' => 'Envoyer aussi par WhatsApp avant l\'échéance',
        'whatsapp_before_due_warning' => '⚠️ L\'envoi de WhatsApp avant l\'échéance coûte des messages supplémentaires.',
        'overdue_auto_title' => 'Rappels automatiques en retard',
        'overdue_auto_desc' => 'Envoyer des rappels automatiques tous les X jours après l\'échéance jusqu\'au paiement.',
        'overdue_repeat_enabled' => 'Activer les rappels récurrents',
        'overdue_repeat_interval' => 'Répéter tous les (jours)',
        'overdue_repeat_interval_hint' => 'Exemple: tous les 7 jours = rappel hebdomadaire',
        'overdue_max_reminders' => 'Nombre maximum de rappels',
        'overdue_max_reminders_hint' => 'Laisser vide pour continuer jusqu\'au paiement',
        'save_settings' => 'Enregistrer les paramètres',
        'saved' => 'Paramètres de rappel enregistrés avec succès',
        'template_variables' => 'Variables disponibles :',
        'email_template' => 'Modèle de message e-mail',
        'whatsapp_template' => 'Modèle de message WhatsApp',
        'info_banner' => 'Le système fonctionne automatiquement chaque jour. Lorsqu\'un nouvel étudiant s\'inscrit avec des frais impayés, le système commence à envoyer des rappels selon le calendrier défini ici.',
        'channels_title' => 'Canaux de Rappel',
        'channel_email' => 'E-mail (Gratuit)',
        'channel_whatsapp' => 'WhatsApp (Configuration requise)',
        'channel_both' => 'Les deux',
        'sub_tabs' => [
            'welcome' => 'Messages de Bienvenue',
            'system' => 'Notifications Système',
            'payment' => 'Rappels de Paiement',
        ],
        'quick_templates_email' => 'Modèles rapides E-mail :',
        'quick_templates_whatsapp' => 'Modèles rapides WhatsApp :',
        'presets' => [
            'formal' => 'Formel',
            'friendly' => 'Amical',
            'urgent' => 'Urgent',
        ],
        'presets_data' => [
            'email' => [
                'formal' => 'Nous vous informons que les frais pour l\'étudiant(e) {student_name} d\'un montant de {amount} sont dus le {due_date}.
Veuillez effectuer le paiement à temps pour assurer la continuité du service éducatif sans interruption.
Merci de votre coopération.
{center_name}',
                'friendly' => 'Bienvenue chez {center_name},
Nous vous rappelons que la date de paiement des frais de {student_name} est le {due_date} (Montant : {amount}).
Nous sommes ravis de vous avoir parmi nous.
Cordialement, l\'administration de {center_name}',
                'urgent' => 'ALERTE IMPORTANTE :
Nous vous informons que les frais de {student_name} d\'un montant de {amount} sont déjà échus depuis le {due_date}.
Veuillez régulariser la situation rapidement pour éviter toute interruption de service.
{center_name}',
            ],
            'whatsapp' => [
                'formal' => 'Rappel officiel : Les frais de {student_name} ({amount}) sont dus le {due_date}. Merci de régulariser. {center_name}',
                'friendly' => 'Bonjour ! Nous vous rappelons le paiement des frais de {student_name} pour le {due_date}. Bonne journée ! 🌸 {center_name}',
                'urgent' => 'ALERTE : Les frais de {student_name} sont en retard depuis {due_date}. Merci de payer dès que possible. {center_name}',
            ],
        ],
        'timeline_preview' => 'Aperçu du Calendrier',
        'timeline_desc' => 'Visualisation des rappels automatiques.',
        'whatsapp_placeholder' => 'Rappel : Les frais de l\'étudiant {student_name} ({amount}) sont dus le {due_date}. Merci de payer. {center_name}',
    ],
    'general_timezone_help' => 'Affecte l\'affichage de la date et de l\'heure dans les rapports',
    'academic_system_defaults_alert' => 'Les paramètres par défaut du système sont actuellement utilisés. Les paramètres personnalisés seront enregistrés une fois que vous aurez cliqué sur Enregistrer.',
    'academic_restore_defaults' => 'Rétablir les paramètres par défaut',
    'updated' => 'Paramètres mis à jour avec succès',
];
