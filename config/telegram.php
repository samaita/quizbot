<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),
    'http_client_handler' => null,
    'base_bot_url' => null,
    'resolve_command_dependencies' => true,
    'commands' => [
        // Your bot commands
    ],
];