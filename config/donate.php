<?php

return [
    'paypal' => [
        'enabled' => true,
        'name' => 'Paypal',
        'route' => 'paypal',
        'currency' => 'USD',
        'image' => 'images/donate/paypal.png',
        //'endpoint' => 'https://api-m.paypal.com', // for prod only
        'endpoint' => 'https://api-m.sandbox.paypal.com', // for sandbox only
        'client_id' => '',
        'secret' => '',
        'webhook_id' => '',
        'package' => [
            [
                'name' => '500 Silk',
                'price' => 5,
                'value' => 500,
                'bonus' => 50,
            ],
            [
                'name' => '1000 Silk',
                'price' => 10,
                'value' => 1000,
                'bonus' => 50,
            ],
            [
                'name' => '2500 Silk',
                'price' => 25,
                'value' => 2500,
                'bonus' => 50,
            ],
        ],
    ],
    'fawaterk' => [
        'enabled' => true,
        'name' => 'Fawaterk',
        'route' => 'fawaterk',
        'currency' => 'USD',
        'image' => 'images/donate/fawaterk.png',
        'endpoint' => 'https://api.fawaterk.com/invoice',
        'key' => '', // Your Fawaterk API Key
        'secret' => '', // Your Fawaterk Secret Key
        'package' => [
            [
                'name' => '100 Silk',
                'price' => 1.00,
                'value' => 100,
            ],
            [
                'name' => '500 Silk',
                'price' => 5.00,
                'value' => 500,
            ],
        ],
    ],
    'coinbase' => [
        'enabled' => true,
        'name' => 'Coinbase',
        'route' => 'coinbase',
        'currency' => 'USD',
        'image' => 'images/donate/coinbase.png',
        'endpoint' => 'https://api.commerce.coinbase.com/charges',
        'api_key' => '', // Your Coinbase API Key
        'package' => [
            [
                'name' => '100 Silk',
                'price' => 1.00,
                'value' => 100,
            ],
            [
                'name' => '500 Silk',
                'price' => 5.00,
                'value' => 500,
            ],
        ],
    ],
    'maxicard' => [
        'enabled' => true,
        'name' => 'Maxigame Card',
        'route' => 'maxicard',
        'currency' => 'TL',
        'image' => 'images/donate/maxicard.png',
        'url' => 'https://www.maxigame.org/epin/yukle.php',
        'key' => '',
        'secret' => '',
        'package' => [
            [
                'name' => '100 Silk',
                'price' => 1,
                'value' => 100,
            ],
            [
                'name' => '500 Silk',
                'price' => 5,
                'value' => 500,
            ],
            [
                'name' => '1000 Silk',
                'price' => 10,
                'value' => 1000,
            ],
            [
                'name' => '2500 Silk',
                'price' => 25,
                'value' => 2500,
            ],
            [
                'name' => '5000 Silk',
                'price' => 50,
                'value' => 5000,
            ],
            [
                'name' => '7500 Silk',
                'price' => 75,
                'value' => 7500,
            ],
            [
                'name' => '10000 Silk',
                'price' => 100,
                'value' => 10000,
            ],
            [
                'name' => '25000 Silk',
                'price' => 250,
                'value' => 25000,
            ],
        ],
    ],
    'hipocard' => [
        'enabled' => true,
        'name' => 'Hipopotamya Card',
        'route' => 'hipocard',
        'currency' => 'TL',
        'image' => 'images/donate/hipocard.png',
        //'url' => 'https://www.hipopotamya.com/api/v1/hipocard/epins',
        'url' => 'https://www.hipopotamya.com/api/sandbox/v1/hipocard/epins',
        'key' => '',
        'secret' => '',
        'package' => [
            [
                'name' => '100 Silk',
                'price' => 1.00,
                'value' => 100,
            ],
            [
                'name' => '500 Silk',
                'price' => 5.00,
                'value' => 500,
            ],
            [
                'name' => '1000 Silk',
                'price' => 10.00,
                'value' => 1000,
            ],
            [
                'name' => '2500 Silk',
                'price' => 25.00,
                'value' => 2500,
            ],
            [
                'name' => '5000 Silk',
                'price' => 50.00,
                'value' => 5000,
            ],
            [
                'name' => '7500 Silk',
                'price' => 75.00,
                'value' => 7500,
            ],
            [
                'name' => '10000 Silk',
                'price' => 100.00,
                'value' => 10000,
            ],
            [
                'name' => '25000 Silk',
                'price' => 250.00,
                'value' => 25000,
            ],
        ],
    ],
    'hipopay' => [
        'enabled' => true,
        'name' => 'Hipopotamya Payment',
        'route' => 'hipopay',
        'currency' => 'TL',
        'image' => 'images/donate/hipopay.png',
        'url' => 'https://www.hipopotamya.com/api/v1/merchants/payment/token', //callback url: http://localhost/callback/hipopay
        'key' => '',
        'secret' => '',
        'commission_type' => 1,
        'package' => [
            [
                'name' => '500 Silk',
                'price' => 5,
                'value' => 500,
            ],
            [
                'name' => '2500 Silk',
                'price' => 25,
                'value' => 2500,
            ],
        ],
    ],
];
