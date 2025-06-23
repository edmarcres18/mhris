<?php

return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT'),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'pem_file' => env('VAPID_PEM_FILE'),
    ],
    'model' => \App\Models\User::class,
    'gcm' => [
        'key' => env('GCM_KEY'),
        'sender_id' => env('GCM_SENDER_ID'),
    ],
    'client_options' => [
        'timeout' => 3.0,
    ],
    
    'ttl' => env('WEBPUSH_TTL', 7200), // Time To Live in seconds
    'timeout' => env('WEBPUSH_TIMEOUT', 30), // Request timeout in seconds
    'automatic_padding' => env('WEBPUSH_AUTOMATIC_PADDING', true),
    
    'icon' => [
        'path' => env('WEBPUSH_ICON_PATH', '/images/notification-icon.png'),
        'badge' => env('WEBPUSH_BADGE_PATH', '/images/notification-badge.png'),
    ],
];
