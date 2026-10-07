<?php
/**
 * Local / Environment Configuration Template
 * Copy this file to config/local.php and customize for your environment.
 * DO NOT commit config/local.php to version control.
 */
return [
    // Base URL path of the application
    // - Local XAMPP/LAMPP: '/test/web_cty'
    // - cPanel Domain Root: '' (or '/')
    // - cPanel Subfolder:   '/web'
    'app_base_url' => '/test/web_cty',

    // Database Connection Settings
    'db_host'      => 'localhost',
    'db_user'      => 'your_database_user',
    'db_pass'      => 'your_database_password',
    'db_name'      => 'your_database_name',
    'db_port'      => 3306,

    // Environment & Debugging
    'app_env'      => 'production', // 'development' or 'production'
    'app_debug'    => false,

    // Allowed CORS Origins for API (optional, e.g. for Vite dev server)
    'cors_allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],
];
