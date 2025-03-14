<?php

return [
    'paths' => ['api/*', 'banners'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],  // Đảm bảo URL frontend chính xác
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
