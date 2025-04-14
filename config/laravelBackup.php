<?php

return [
    'backup_path' => storage_path('backups'),
    'keep_days' => 5,
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],
    'backup_storage_directory' => false, // true or false
    'check_access' => false, // true or false
    'allowed_roles' => [], // Role Names Example: ['Admin', 'Super-Admin','Developer', 'Manager']
];
