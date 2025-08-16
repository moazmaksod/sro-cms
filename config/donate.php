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
        'client_id' => 'PAYPAL_CLIENT_ID',
        'secret' => 'PAYPAL_SECRET',
        'webhook_id' => 'PAYPAL_WEBHOOK_ID',
        'package' => [
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
        ],
    ],
    'stripe' => [
        'enabled' => true,
        'name' => 'Stripe',
        'route' => 'stripe',
        'currency' => 'USD',
        'image' => 'images/donate/stripe.png',
        'endpoint' => 'https://api.stripe.com',
        'secret_key' => 'STRIPE_SECRET_KEY',
        'publishable_key' => 'STRIPE_PUBLISHABLE_KEY',
        'webhook_secret' => 'STRIPE_WEBHOOK_SECRET',
        'package' => [
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
        ],
    ],
    'coinpayments' => [
        'enabled' => false,
        'name' => 'CoinPayments',
        'route' => 'coinpayments',
        'currency' => 'USD',
        'image' => 'images/donate/coinpayments.png',
        'endpoint' => 'https://api.coinpayments.com',
        'merchant_id' => 'COINPAYMENTS_MERCHANT_ID',
        'client_id' => 'COINPAYMENTS_CLIENT_ID',
        'client_secret' => 'COINPAYMENTS_CLIENT_SECRET',
        'ipn_secret' => 'COINPAYMENTS_IPN_SECRET',
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
    'fawaterk' => [
        'enabled' => true,
        'name' => 'Fawaterk',
        'route' => 'fawaterk',
        'currency' => 'EGP',
        'image' => 'images/donate/fawaterk.png',
        //'endpoint' => 'https://app.fawaterk.com', // for production
        'endpoint' => 'https://staging.fawaterk.com', // for sandbox
        'key' => 'FAWATERK_API_KEY',
        'secret' => 'FAWATERK_PROVIDER_KEY',
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
    'maxicard' => [
        'enabled' => false,
        'name' => 'MaxiCard',
        'route' => 'maxicard',
        'currency' => 'TL',
        'image' => 'images/donate/maxicard.png',
        'url' => 'https://www.maxigame.org/epin/yukle.php',
        'key' => 'MAXICARD_API_KEY',
        'secret' => 'MAXICARD_API_PASSWORD',
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
        'enabled' => false,
        'name' => 'HipoCard',
        'route' => 'hipocard',
        'currency' => 'TL',
        'image' => 'images/donate/hipocard.png',
        //'url' => 'https://www.hipopotamya.com/api/v1/hipocard/epins', // for production
        'url' => 'https://www.hipopotamya.com/api/sandbox/v1/hipocard/epins', // for sandbox
        'key' => 'HIPOCARD_API_KEY',
        'secret' => 'HIPOCARD_API_PASSWORD',
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
        'name' => 'HipoPay',
        'route' => 'hipopay',
        'currency' => 'TL',
        'image' => 'images/donate/hipopay.png',
        'url' => 'https://www.hipopotamya.com/api/v1/merchants/payment/token', //callback url: http://localhost/callback/hipopay
        'key' => 'HIPOPAY_API_KEY',
        'secret' => 'HIPOPAY_API_PASSWORD',
        'commission_type' => 1,
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
];
