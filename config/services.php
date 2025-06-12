<?php

return [
    'nfe' => [
        'api_key' => env('NFE_API_KEY', 'xjaz2NrFhGX4vRZ9uwCSK0LqzwYZWzyz2FjT3VGOxd2hGo8DJ5Y0imSHRU5v4wW0xJQ'),
    ],
    'ibpt' => [
        'api_key' => env('IBPT_API_KEY', 'ViRtv4Ple2BZ7g406OIZlDjMpSGOqYDjMfg4fp-jeKajN86xJtEfm1ZT-pNVvQiY'),
    ],
    'gtin' => [
        'logins' => [
            'acesso_01' => [
                'username' => 'josehs20',
                'password' => '123456789'
            ],

        ]
    ],
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

];
