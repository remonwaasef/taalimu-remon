<?php

return [
    'privacy' => [
        'last_updated' => 'Last Updated',
        'introduction' => [
            'title' => 'Introduction',
            'content' => 'We are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and protect your information when you use our educational center management platform.',
        ],
        'data_collection' => [
            'title' => 'Data We Collect',
            'content' => 'We collect the following types of information:',
            'items' => [
                'name' => 'Personal identifiers (name, email, phone number)',
                'email' => 'Account credentials',
                'contact' => 'Educational center information',
                'usage' => 'Usage data and analytics',
            ],
        ],
        'usage' => [
            'title' => 'How We Use Your Data',
            'content' => 'We use your data to provide our services, improve user experience, send notifications, and comply with legal obligations.',
        ],
        'rights' => [
            'title' => 'Your Rights (GDPR)',
            'content' => 'Under GDPR, you have the following rights:',
            'items' => [
                'access' => 'Right to access your personal data',
                'rectification' => 'Right to rectify inaccurate data',
                'deletion' => 'Right to erasure ("right to be forgotten")',
                'portability' => 'Right to data portability',
                'objection' => 'Right to object to processing',
            ],
        ],
        'security' => [
            'title' => 'Data Security',
            'content' => 'We use industry-standard encryption (AES-256) and security measures to protect your data. All data is stored in secure data centers.',
        ],
        'contact' => [
            'title' => 'Contact Us',
            'content' => 'For privacy-related inquiries, please contact us at privacy@'.config('app.tenant_domain'),
        ],
    ],

    'terms' => [
        'last_updated' => 'Last Updated',
        'acceptance' => [
            'title' => 'Acceptance of Terms',
            'content' => 'By accessing and using this platform, you accept and agree to be bound by these Terms of Service.',
        ],
        'services' => [
            'title' => 'Description of Services',
            'content' => 'We provide a comprehensive platform for educational center management including student enrollment, attendance tracking, billing, and more.',
        ],
        'user_obligations' => [
            'title' => 'User Obligations',
            'content' => 'Users must provide accurate information, maintain account security, and use the service in compliance with applicable laws.',
        ],
        'liability' => [
            'title' => 'Limitation of Liability',
            'content' => 'We are not liable for any indirect, incidental, or consequential damages arising from the use of our services.',
        ],
        'contact' => [
            'title' => 'Contact',
            'content' => 'For questions about these terms, contact legal@'.config('app.tenant_domain'),
        ],
    ],

    'cookies' => [
        'last_updated' => 'Last Updated',
        'what' => [
            'title' => 'What are Cookies?',
            'content' => 'Cookies are small text files stored on your device when you visit our website. They help us provide a better user experience.',
        ],
        'types' => [
            'title' => 'Types of Cookies We Use',
        ],
        'manage' => [
            'title' => 'Manage Your Preferences',
            'content' => 'You can change your cookie preferences at any time using the button below:',
        ],
    ],
];
