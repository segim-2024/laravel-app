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

    'sso' => [
        'key' => env('SSO_KEY'),
    ],

    'pamus' => [
        'ip' => env('PAMUS_IP'),
        'api_key' => env('PAMUS_API_KEY'),
        'point_api_key' => env('PAMUS_POINT_API_KEY'),
    ],

    'portone' => [
        'v2' => [
            'web_hook_url' => env('PORTONE_WEB_HOOK_URL'),
            'key' => env('PORTONE_API_KEY'),
            'channel_key' => env('PORTONE_CHANNEL_KEY'),
        ],
    ],

    'mts' => [
        'auth_code' => env('MTS_AUTH_CODE'),
        'sender_key' => env('MTS_SENDER_KEY'),
    ],

    'library' => [
        // 아웃바운드: 이 앱이 라이브러리 서버를 호출할 때 요청 서명에 사용
        'api_key' => env('LIBRARY_API_KEY'),

        // 인바운드: 라이브러리 서버가 이 앱의 /library-api 를 호출할 때 검증
        'inbound' => [
            'api_key' => env('LIBRARY_INBOUND_API_KEY'),
            'ips' => env('LIBRARY_INBOUND_IPS'),
        ],
    ],

    'segim' => [
        'api_key' => env('SEGIM_API_KEY'),
    ],
];
