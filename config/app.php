<?php

return [
    'name' => env('APP_NAME', 'Shop'),
    'env' => env('APP_ENV', 'local'),
    'debug' => (bool) env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'key' => env('APP_KEY', 'base64:'.base64_encode(random_bytes(32))),
];
