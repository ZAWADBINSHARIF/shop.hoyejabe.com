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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'smsq' => [
        'endpoint' => env('SMSQ_ENDPOINT', 'https://console.smsq.global/api/v2/SendSMS'),
        'api_key' => env('SMSQ_API_KEY'),
        'client_id' => env('SMSQ_CLIENT_ID'),
        'sender_id' => env('SMSQ_SENDER_ID', 'LIONBD'),
        'method' => env('SMSQ_METHOD', 'GET'), // GET or POST
        'templates' => [
            'verification' => env('SMSQ_TEMPLATE_VERIFICATION'),
            'login' => env('SMSQ_TEMPLATE_LOGIN'),
            'password_reset' => env('SMSQ_TEMPLATE_PASSWORD_RESET'),
            'order_confirmation' => env('SMSQ_TEMPLATE_ORDER_CONFIRMATION'),
        ],
    ],

];
