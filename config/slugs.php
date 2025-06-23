<?php

return [
    'length' => 8,
    'cache_duration' => 86400, // 24 hours in seconds
    'excluded_paths' => [
        'api/*',
        '_debugbar/*',
        'livewire/*',
        'sanctum/*',
        'login',
        'register',
        'password/*',
    ],
];
