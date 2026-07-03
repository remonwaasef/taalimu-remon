<?php

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => 'Taalimu', // set false to total remove
            'titleBefore' => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => 'نموذج لإدارة المراكز التعليمية', // set false to total remove
            'separator' => ' - ',
            'keywords' => ['برنامج إدارة مراكز', 'نظام تعليمي', 'إدارة مدارس', 'Taalimu', 'EduCentral'],
            'canonical' => false, // Set to null or 'full' to use Url::full(), or a string value
            'robots' => 'index,follow', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are used by Google, Bing, Alexa, etc. Varies with each registering.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => 'Taalimu', // set false to total remove
            'description' => 'إدارة المراكز التعليمية بذكاء', // set false to total remove
            'url' => false, // Set null for using Url::current(), set false to total remove
            'type' => 'website',
            'site_name' => 'Taalimu',
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default configurations to be used by the twitter cards generator.
         */
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@taalimu',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => 'Taalimu', // set false to total remove
            'description' => 'نظام متكامل لإدارة المراكز والمدارس', // set false to total remove
            'url' => false, // Set null for using Url::current(), set false to total remove
            'type' => 'WebApplication',
            'images' => [],
        ],
    ],
];
