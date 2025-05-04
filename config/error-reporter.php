<?php

return [
    'channels' => [
        'discord' => [
            'enabled' => env('ERROR_REPORTER_DISCORD_ENABLED', false),
            'webhook_url' => env('ERROR_REPORTER_DISCORD_WEBHOOK'),
            'username' => env('ERROR_REPORTER_DISCORD_USERNAME', 'Error Reporter'),
        ],
        'email' => [
            'enabled' => env('ERROR_REPORTER_EMAIL_ENABLED', false),
            'to' => env('ERROR_REPORTER_EMAIL_TO'),
        ],
        'slack' => [
            'enabled' => env('ERROR_REPORTER_SLACK_ENABLED', false),
            'webhook_url' => env('ERROR_REPORTER_SLACK_WEBHOOK'),
            'username' => env('ERROR_REPORTER_SLACK_USERNAME', 'Error Reporter'),
            'icon_emoji' => env('ERROR_REPORTER_SLACK_ICON', ':warning:'),
        ],
    ],
];