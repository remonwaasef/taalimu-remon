<?php

return [
    'title' => 'Configuration du Centre | Plateforme Éducative',
    'welcome_title' => 'Bienvenue sur votre nouvelle plateforme éducative 🚀',
    'welcome_subtitle' => 'Préparons votre centre pour le lancement en seulement 4 étapes simples.',
    'security_note' => 'Les informations et données de votre centre sont entièrement cryptées et sécurisées',
    
    'steps' => [
        'step_1' => 'Paramètres de base',
        'step_2' => 'Enseignants',
        'step_3' => 'Cours et Matières',
        'step_4' => 'Étudiants',
    ],
    
    'step_1' => [
        'title' => 'Paramètres de base',
        'subtitle' => 'Commençons par configurer les options d\'affichage de votre plateforme.',
        'language_label' => 'Langue système par défaut',
        'currency_label' => 'Devise locale',
        'btn_submit' => 'Enregistrer et continuer',
    ],
    
    'step_2' => [
        'title' => 'Ajouter le premier enseignant',
        'subtitle' => 'Ajoutez le premier enseignant à votre plateforme pour commencer à lier des cours.',
        'name_label' => 'Nom de l\'enseignant',
        'name_placeholder' => 'ex: M. Jean Dupont',
        'specialization_label' => 'Spécialisation',
        'specialization_placeholder' => 'ex: Mathématiques, Sciences',
        'email_label' => 'Adresse Email (Optionnel)',
        'email_placeholder' => 'instructeur@exemple.com',
        'phone_label' => 'Numéro de téléphone',
        'phone_hint' => '(Sera utilisé pour la connexion)',
        'btn_skip' => 'Ignorer cette étape pour l\'instant',
        'btn_submit' => 'Ajouter et continuer',
    ],

    'step_3' => [
        'title' => 'Créer des cours',
        'subtitle' => 'Ajoutez votre première matière éducative ou cours de formation.',
        'name_label' => 'Nom de la matière / du cours',
        'name_placeholder' => 'ex: Mathématiques - 10ème année',
        'btn_skip' => 'Ignorer pour l\'instant',
        'btn_submit' => 'Créer le cours',
    ],

    'step_4' => [
        'title' => 'Dernière étape : Premier étudiant 🎉',
        'subtitle' => 'Inscrivez le premier étudiant sur votre plateforme pour commencer les interactions.',
        'name_label' => 'Nom de l\'étudiant',
        'name_placeholder' => 'ex: Alex Martin',
        'phone_label' => 'Numéro de téléphone de l\'étudiant',
        'btn_skip' => 'Ignorer et aller au tableau de bord',
        'btn_submit' => 'Terminer la configuration !',
    ],

    'loading' => 'Traitement des données en cours...',
    'error_title' => 'Oups',
    'error_fallback' => 'Une erreur inattendue est survenue',
    'btn_ok' => 'OK',
    'currencies' => [
        'egp' => 'Livre Égyptienne (EGP)',
        'sar' => 'Riyal Saoudien (SAR)',
        'aed' => 'Dirham des Émirats (AED)',
        'usd' => 'Dollar Américain (USD)',
        'eur' => 'Euro (EUR)',
    ],
    'btn_back' => 'Retour',
];
