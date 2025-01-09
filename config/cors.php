<?php


return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000', 'https://brisapp-nextjs.vercel.app'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'X-CSRF-TOKEN', 'Authorization'],

    'exposed_headers' => ['X-CSRF-TOKEN'],

    'max_age' => 0,

    'supports_credentials' => true,
];

