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

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],


    // Permitir varios orígenes (especifica los dominios)
    'allowed_origins' => [
        'http://localhost:3000', // Origen local (por ejemplo, frontend en desarrollo)
        'https://*.congeladosbrisamar.es', // Origen de producción
        'http://localhost:5173',
    ],


    'allowed_origins_patterns' => [],

    'allowed_headers' => ['content-type', 'Authorization', 'X-Requested-With', 'X-Token-Auth'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
