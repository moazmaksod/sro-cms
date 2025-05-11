<?php

namespace App\Services;

use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Portal\AphChangedSilk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DonateService
{
    public function processPaypal(Request $request)
    {
        $price = number_format($request->input('price'), 2, '.', '');
        if (!is_numeric($price) || $price <= 0) {
            throw new \Exception('Invalid price provided.');
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

    public function callbackPaypal(Request $request)
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

            $purchasePrice = isset($responseData['purchase_units'][0]['amount']['value']) ? floatval($responseData['purchase_units'][0]['amount']['value']) : null;
            if (!$purchasePrice) {
                throw new \Exception('Payment capture failed: Missing price.');
            }

            $package = collect($config['package'])->firstWhere('price', $purchasePrice);
            if (!$package) {
                throw new \Exception('Invalid package price.');
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

            return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
        }

        return back()->withErrors(['paypal' => 'Failed to processes payment!'])->withInput();
    }

    private function getPaypalAccessToken()
    {
        $config = config('donate.paypal');
        return cache()->remember('paypal_access_token', 540, function () use ($config) {
            $authResponse = Http::withBasicAuth($config['client_id'], $config['secret'])
                ->asForm()
                ->post($config['endpoint'].'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($authResponse->failed()) {
                throw new \Exception('Failed to retrieve PayPal access token.');
            }

            return $authResponse->json()['access_token'];
        });
    }

    public function processMaxicard(Request $request)
    {
        $config = config('donate.maxicard');

        $request->validate([
            'code' => 'required',
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!$config['key'] || !$config['secret'] || !$config['url']) {
            throw new \Exception('API credentials are missing.');
        }

        $xml = "<APIRequest>
                <params>
                    <username>{$config['key']}</username>
                    <password>{$config['secret']}</password>
                    <cmd>epinadd</cmd>
                    <epinusername>{$user->jid}</epinusername>
                    <epincode>{$request->code}</epincode>
                    <epinpass>{$request->password}</epinpass>
                </params>
            </APIRequest>";

        $response = Http::send('post', $config['url'], [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                "Cache-Control" => "no-cache",
            ],
            'form_params' => [
                'data' => urlencode($xml)
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to communicate with Maxicard API.');
        }

        $responseObject = simplexml_load_string($response->body());
        if(trim($responseObject->params->durum) == 'ok' && intval(trim($responseObject->params->siparis_no)) > 0) {
            $orderNumber = intval(trim($responseObject->params->siparis_no));
            $commission = preg_replace('/[^0-9\.]/', '', trim($responseObject->params->komisyon));

            $amount = intval(preg_replace('/[^0-9]/', '', $responseObject->params->tutar));
            if (!$amount || $amount <= 0) {
                return back()->withErrors(['maxicard' => 'This epin is invalid, Please try a valid one.'])->withInput();
            }

            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $amount);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $amount);
            }

            DonateLog::setDonateLog(
                'Maxicard',
                (string) Str::uuid(),
                'true',
                $amount,
                $amount,
                "User:{$user->username} purchased Silk for {$amount} using Maxicard. Order Number: {$orderNumber}.",
                $user->jid,
                $request->ip()
            );

            return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
        }else {
            return back()->withErrors(['maxicard' => "Payment failed: {$responseObject->params->durum}"])->withInput();
        }
    }

    public function processHipocard(Request $request)
    {
        $config = config('donate.hipocard');
        $request->validate([
            'code' => 'required',
            'password' => 'required',
        ]);

        if (!$config['url'] || !$config['key'] || !$config['secret']) {
            throw new \Exception('API credentials are missing.');
        }

        $payload = [
            'epin_code' => $request->code,
            'epin_secret' => $request->password,
            'player_name' => Auth::user()->username,
            'used_ip' => $request->ip(),
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'api-key' => $config['key'],
            'api-secret' => $config['secret'],
        ])->post($config['url'], $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to communicate with the Hipopotamya API.');
        }

        $responseData = $response->json();
        if (isset($responseData['success']) && $responseData['success'] === true) {
            $package = collect($config['package'])->firstWhere('price', intval($responseData['data']['total_sales']));
            if (!$package) {
                throw new \Exception('Invalid package price.');
            }

            $user = Auth::user();
            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            DonateLog::setDonateLog(
                'Hipocard',
                (string) Str::uuid(),
                'true',
                $package['price'],
                $package['value'],
                "User:{$user->username} purchased Silk for {$package['price']} using Hipocard.",
                $user->jid,
                $request->ip()
            );

            return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
        } else {
            return back()->withErrors(['hipocard' => "Payment failed: {$responseData['message']}"])->withInput();
        }
    }
}
