<?php

return [
    'driver' => env('MARKET_API_DRIVER', 'mock'),

    'mock' => [
        'google-play' => \App\Services\MarketAPI\MockAPI\GooglePlayAPI::class,
        'app-store' => \App\Services\MarketAPI\MockAPI\AppStoreAPI::class
    ],

    'production' => [
        'google-play' => null,
        'app-store' => null
    ]
];