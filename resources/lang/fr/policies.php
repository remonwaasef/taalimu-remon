<?php

return [
    'privacy' => [
        'last_updated' => 'Dernière Mise à Jour',
        'introduction' => [
            'title' => 'Introduction',
            'content' => 'Nous nous engageons à protéger vos données personnelles. Cette politique de confidentialité explique comment nous collectons, utilisons et protégeons vos informations lorsque vous utilisez notre plateforme de gestion de centres éducatifs.',
        ],
        'data_collection' => [
            'title' => 'Données que Nous Collectons',
            'content' => 'Nous collectons les types d\'informations suivants :',
            'items' => [
                'name' => 'Identifiants personnels (nom, email, téléphone)',
                'email' => 'Identifiants de compte',
                'contact' => 'Informations du centre éducatif',
                'usage' => 'Données d\'utilisation et analyses',
            ],
        ],
        'usage' => [
            'title' => 'Comment Nous Utilisons Vos Données',
            'content' => 'Nous utilisons vos données pour fournir nos services, améliorer l\'expérience utilisateur, envoyer des notifications et se conformer aux obligations légales.',
        ],
        'rights' => [
            'title' => 'Vos Droits (RGPD)',
            'content' => 'En vertu du RGPD, vous disposez des droits suivants :',
            'items' => [
                'access' => 'Droit d\'accès à vos données personnelles',
                'rectification' => 'Droit de rectifier les données inexactes',
                'deletion' => 'Droit à l\'effacement ("droit à l\'oubli")',
                'portability' => 'Droit à la portabilité des données',
                'objection' => 'Droit d\'opposition au traitement',
            ],
        ],
        'security' => [
            'title' => 'Sécurité des Données',
            'content' => 'Nous utilisons un chiffrement standard (AES-256) et des mesures de sécurité pour protéger vos données. Toutes les données sont stockées dans des centres de données sécurisés.',
        ],
        'contact' => [
            'title' => 'Contactez-Nous',
            'content' => 'Pour les questions relatives à la confidentialité, contactez-nous à privacy@'.config('app.tenant_domain'),
        ],
    ],

    'terms' => [
        'last_updated' => 'Dernière Mise à Jour',
        'acceptance' => [
            'title' => 'Acceptation des Conditions',
            'content' => 'En accédant et en utilisant cette plateforme, vous acceptez et vous engagez à respecter ces conditions d\'utilisation.',
        ],
        'services' => [
            'title' => 'Description des Services',
            'content' => 'Nous fournissons une plateforme complète pour la gestion de centres éducatifs, incluant l\'inscription des étudiants, le suivi de présence, la facturation, et plus encore.',
        ],
        'user_obligations' => [
            'title' => 'Obligations de l\'Utilisateur',
            'content' => 'Les utilisateurs doivent fournir des informations exactes, maintenir la sécurité du compte et utiliser le service conformément aux lois applicables.',
        ],
        'liability' => [
            'title' => 'Limitation de Responsabilité',
            'content' => 'Nous ne sommes pas responsables des dommages indirects, accessoires ou consécutifs découlant de l\'utilisation de nos services.',
        ],
        'contact' => [
            'title' => 'Contact',
            'content' => 'Pour les questions concernant ces conditions, contactez legal@'.config('app.tenant_domain'),
        ],
    ],

    'cookies' => [
        'last_updated' => 'Dernière Mise à Jour',
        'what' => [
            'title' => 'Que Sont les Cookies ?',
            'content' => 'Les cookies sont de petits fichiers texte stockés sur votre appareil lorsque vous visitez notre site. Ils nous aident à offrir une meilleure expérience utilisateur.',
        ],
        'types' => [
            'title' => 'Types de Cookies que Nous Utilisons',
        ],
        'manage' => [
            'title' => 'Gérer Vos Préférences',
            'content' => 'Vous pouvez modifier vos préférences de cookies à tout moment en utilisant le bouton ci-dessous :',
        ],
    ],
];
