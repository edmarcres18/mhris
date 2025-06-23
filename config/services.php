<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
//     'openai' => [
//     'api_key' => env('OPENAI_API_KEY'),
//     'organization' => env('OPENAI_ORGANIZATION'),  // Optional
// ],

    'firebase' => [
        'server_key' => env('FIREBASE_SERVER_KEY'),
        'sender_id' => env('FIREBASE_SENDER_ID'),
    ],

    'indeed' => [
        'publisher_id' => env('INDEED_PUBLISHER_ID'),
        'api_token' => env('INDEED_API_TOKEN'),
    ],

    'youtube-dl' => [
        'binary_path' => env('YOUTUBE_DL_BINARY', '/usr/local/bin/yt-dlp'),
        'python_path' => env('YOUTUBE_DL_PYTHON', '/usr/bin/python3'),
        'temp_path' => storage_path('app/public/temp'),
    ],

    'newsapi' => [
        'key' => env('NEWS_API_KEY'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

];
