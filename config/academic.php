<?php

return [
    'templates' => [
        'egyptian_national' => [
            'name' => 'center::academic.egyptian_national',
            'stages' => [
                [
                    'name' => 'center::academic.stages.primary',
                    'grades' => [
                        'الصف الأول الابتدائي',
                        'الصف الثاني الابتدائي',
                        'الصف الثالث الابتدائي',
                        'الصف الرابع الابتدائي',
                        'الصف الخامس الابتدائي',
                        'الصف السادس الابتدائي',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.preparatory',
                    'grades' => [
                        'الصف الأول الإعدادي',
                        'الصف الثاني الإعدادي',
                        'الصف الثالث الإعدادي',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.secondary',
                    'grades' => [
                        'الصف الأول الثانوي',
                        'الصف الثاني الثانوي',
                        'الصف الثالث الثانوي',
                    ],
                ],
            ],
        ],
        'egyptian_azhar' => [
            'name' => 'center::academic.egyptian_azhar',
            'stages' => [
                [
                    'name' => 'center::academic.stages.azhar_primary',
                    'grades' => [
                        'الصف الأول الابتدائي',
                        'الصف الثاني الابتدائي',
                        'الصف الثالث الابتدائي',
                        'الصف الرابع الابتدائي',
                        'الصف الخامس الابتدائي',
                        'الصف السادس الابتدائي',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.azhar_preparatory',
                    'grades' => [
                        'الصف الأول الإعدادي',
                        'الصف الثاني الإعدادي',
                        'الصف الثالث الإعدادي',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.azhar_secondary',
                    'grades' => [
                        'الاول الثانوي',
                        'الثاني الثانوي',
                        'الثالث الثانوي',
                    ],
                ],
            ],
        ],
        'french_system' => [
            'name' => 'center::academic.french_system',
            'stages' => [
                [
                    'name' => 'center::academic.stages.primaire',
                    'grades' => [
                        'CP',
                        'CE1',
                        'CE2',
                        'CM1',
                        'CM2',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.college',
                    'grades' => [
                        '6ème',
                        '5ème',
                        '4ème',
                        '3ème',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.lycee',
                    'grades' => [
                        'Seconde',
                        'Première',
                        'Terminale',
                    ],
                ],
            ],
        ],
        'european_system' => [
            'name' => 'center::academic.european_system',
            'stages' => [
                [
                    'name' => 'center::academic.stages.pyp',
                    'grades' => [
                        'Year 1',
                        'Year 2',
                        'Year 3',
                        'Year 4',
                        'Year 5',
                        'Year 6',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.myp',
                    'grades' => [
                        'Year 7',
                        'Year 8',
                        'Year 9',
                        'Year 10',
                        'Year 11',
                    ],
                ],
                [
                    'name' => 'center::academic.stages.dp',
                    'grades' => [
                        'Year 12 (DP1)',
                        'Year 13 (DP2)',
                    ],
                ],
            ],
        ],
    ],

    'late_rules' => [
        'defaults' => [
            ['minutes' => 15, 'label' => 'center::academic.late_rules.slight_delay'],
            ['minutes' => 30, 'label' => 'center::academic.late_rules.half_hour_delay'],
            ['minutes' => 60, 'label' => 'center::academic.late_rules.hour_delay'],
        ],
    ],
];
