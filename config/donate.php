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
    'coinpayments' => [
        'enabled' => true,
        'name' => 'CoinPayments',
        'route' => 'coinpayments',
        'currency' => 'USD',
        'image' => 'images/donate/coinpayments.png',
        'endpoint' => 'https://api.coinpayments.com',
        'merchant_id' => '',
        'client_id' => '6c79ab35d6104ead9054fbdd56b56224',
        'client_secret' => 'tEdl2dD8a0dxzPrrfd9PngyR8KtSk47jT/7v3EHss0U=',
        'ipn_secret' => '',
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
    'fawaterk' => [
        'enabled' => true,
        'name' => 'Fawaterk',
        'route' => 'fawaterk',
        'currency' => 'EGP',
        'image' => 'images/donate/fawaterk.png',
        //'endpoint' => 'https://app.fawaterk.com',
        'endpoint' => 'https://staging.fawaterk.com',
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
