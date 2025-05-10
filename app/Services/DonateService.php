<?php

namespace App\Services;

use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Portal\AphChangedSilk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DonateService
{
    public function processPaypal(Request $request)
    {
        $price = number_format($request->input('price', 5), 2, '.', '');

        if (!is_numeric($price) || $price <= 0) {
            return back()->withErrors(['price' => 'Invalid price provided.']);
        }

        $config = config('donate.paypal');
        $accessToken = $this->getPaypalAccessToken();

        $body = [
            "intent" => "CAPTURE",
            "payment_source" => [
                "paypal" => [
                    "experience_context" => [
                        "return_url" => route('callback', ['method' => 'paypal']),
                        "cancel_url" => route('profile.donate'),
                    ],
                ],
            ],
            "purchase_units" => [
                [
                    "invoice_id" => (string) Str::uuid(),
                    "amount" => [
                        "currency_code" => strtoupper($config['currency']),
                        "value" => $price
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => (string) Str::uuid(),
            'Authorization' => "Bearer $accessToken",
        ])->post($config['endpoint'].'/v2/checkout/orders', $body);

        if ($response->successful()) {
            $approvalLink = collect($response->json('links'))->firstWhere('rel', 'payer-action');

            if ($approvalLink && isset($approvalLink['href'])) {
                return redirect()->away($approvalLink['href']);
            }
        }

        return back()->withErrors(['paypal' => 'Failed to create order!'])->withInput();
    }

    public function handlePaypalCallback(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $config = config('donate.paypal');
        $token = $request->get('token');
        $accessToken = $this->getPaypalAccessToken();

        $captureResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => (string) Str::uuid(),
            'Authorization' => "Bearer $accessToken",
        ])->get($config['endpoint']."/v2/checkout/orders/{$token}");

        if ($captureResponse->successful()) {
            $responseData = $captureResponse->json();
            $purchasePrice = isset($responseData['purchase_units'][0]['amount']['value'])
                ? floatval($responseData['purchase_units'][0]['amount']['value'])
                : null;

            if (!$purchasePrice) {
                return back()->withErrors(['paypal' => 'Payment capture failed: Missing price.'])->withInput();
            }

            $package = collect($config['package'])->firstWhere('price', $purchasePrice);
            if (!$package) {
                return back()->withErrors(['paypal' => 'Invalid package price.'])->withInput();
            }

            $user = Auth::user();

            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            DonateLog::setDonateLog(
                'PayPal',
                (string) Str::uuid(),
                'true',
                $purchasePrice,
                $package['value'],
                "User:{$user->username} purchased {$package['name']} for \${$purchasePrice}.",
                $user->jid,
                $request->ip()
            );

            return redirect()->route('profile.donate')->with('success', 'Payment captured successfully!');
        }

        return back()->withErrors(['paypal' => 'Failed to capture payment!'])->withInput();
    }

    private function getPaypalAccessToken()
    {
        $config = config('donate.paypal');
        $clientId = $config['client_id'];
        $clientSecret = $config['secret'];

        return cache()->remember('paypal_access_token', 540, function () use ($clientId, $clientSecret, $config) {
            $authResponse = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->post($config['endpoint'].'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($authResponse->failed()) {
                throw new \Exception('Failed to retrieve PayPal access token: ' . $authResponse->json()['error_description']);
            }

            return $authResponse->json()['access_token'];
        });
    }

    public function processBinance($amount)
    {
        return [
            'status' => 'success',
            'gateway' => 'binance',
            'amount' => $amount,
            'currency' => 'USDT',
        ];
    }

    public function handleBinanceCallback(Request $request)
    {
        return response()->json(['status' => 'Binance callback received']);
    }
}
