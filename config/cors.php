<?php

/*
 * Konfigurasi CORS (Cross-Origin Resource Sharing).
 * Mengizinkan frontend Next.js (localhost:3000) mengakses API backend.
 */

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
