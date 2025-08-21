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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'telegram-bot-api' => [
        // 'token' => env('TELEGRAM_BOT_TOKEN'),
        // 'chat_id' => env('TELEGRAM_CHAT_ID'),
        'media' => [
            'bot_token' => env('TELEGRAM_MEDIA_BOT_TOKEN'),
            'chat_id'   => env('TELEGRAM_MEDIA_CHAT_ID'),
        ],
        'boost' => [
            'bot_token' => env('TELEGRAM_BOOST_BOT_TOKEN'),
            'chat_id'   => env('TELEGRAM_BOOST_CHAT_ID'),
        ],
        'it' => [
            'bot_token' => env('TELEGRAM_IT_BOT_TOKEN'),
            'chat_id'   => env('TELEGRAM_IT_CHAT_ID'),
        ],
    ],

];
