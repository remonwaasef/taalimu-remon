<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SLA Settings (in minutes)
    |--------------------------------------------------------------------------
    |
    | Define response and resolution requirements for different severity levels.
    |
    */
    'sla' => [
        'critical' => [
            'response' => 15,    // 15 minutes to acknowledge
            'resolution' => 60,  // 1 hour to resolve
        ],
        'high' => [
            'response' => 60,    // 1 hour to acknowledge
            'resolution' => 240, // 4 hours to resolve
        ],
        'medium' => [
            'response' => 240,   // 4 hours to acknowledge
            'resolution' => 1440, // 24 hours to resolve
        ],
        'low' => [
            'response' => 1440,  // 24 hours to acknowledge
            'resolution' => 4320, // 72 hours to resolve
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure how and when admins are notified about issues.
    |
    */
    'notifications' => [
        'email' => env('ISSUE_NOTIFY_EMAIL', true),
        'critical_immediate' => true,
        'digest' => 'daily', // Options: daily, weekly, none
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Mute Threshold
    |--------------------------------------------------------------------------
    |
    | Number of occurrences before an issue is automatically muted
    | to prevent notification spam.
    |
    */
    'auto_mute_threshold' => 100,

    /*
    |--------------------------------------------------------------------------
    | Data Retention (in days)
    |--------------------------------------------------------------------------
    |
    | How long to keep resolved or closed issues in the database.
    |
    */
    'retention' => [
        'resolved' => 90,
        'closed' => 30,
        'deleted' => 7,
    ],
];
