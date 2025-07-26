<?php

return [

    'name' => env('APP_NAME') ?: 'Alphen Beauty Lounge',
    'env' => env('APP_ENV') ?: 'production',
    'debug' => (bool)(env('APP_DEBUG') ?? false),
    'url' => env('APP_URL') ?: 'https://alphenbeauylounge.nl',

    'timezone' => 'Europe/Amsterdam',

    'locale' => env('APP_LOCALE') ?: 'nl',
    'fallback_locale' => env('APP_FALLBACK_LOCALE') ?: 'en',
    'faker_locale' => env('APP_FAKER_LOCALE') ?: 'en_US',

    'key' => env('APP_KEY') ?: 'base64:YOUR_HARD_CODED_BACKUP_KEY',
    'cipher' => 'AES-256-CBC',

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
