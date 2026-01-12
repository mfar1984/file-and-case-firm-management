<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Disable default CORS for api/* routes - using DynamicCorsMiddleware instead
    'paths' => ['sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:8000',
        'https://naaelahsaleh.co',
        'https://www.naaelahsaleh.co',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Accept',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
    ],

    'exposed_headers' => [
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
    ],

    'max_age' => 86400, // 24 hours

    'supports_credentials' => true,

];
