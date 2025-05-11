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
                        "value" => number_format($request->input('price'), 2, '.', '')
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

        return back()->withErrors(['paypal' => "Failed to create order! {$response->json()['error_description']}"])->withInput();
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

            $package = collect($config['package'])->firstWhere('price', $responseData['purchase_units'][0]['amount']['value']);
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
                $package['price'],
                $package['value'],
                "User:{$user->username} purchased {$package['name']} for \${$package['price']}.",
                $user->jid,
                $request->ip()
            );

            return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
        }

        return back()->withErrors(['paypal' => "Failed to processes payment! {$captureResponse->json()['error_description']}"])->withInput();
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
                return back()->withErrors(['paypal' => "Failed to retrieve PayPal access token! {$authResponse->json()['error_description']}"])->withInput();
            }

            return $authResponse->json()['access_token'];
        });
    }

    public function processMaxicard(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'password' => 'required',
        ]);

        $config = config('donate.maxicard');

        $xml = "<APIRequest>
                <params>
                    <username>{$config['key']}</username>
                    <password>{$config['secret']}</password>
                    <cmd>epinadd</cmd>
                    <epinusername>".Auth::user()->jid."</epinusername>
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

        if ($response->successful()) {
            $responseObject = simplexml_load_string($response->body());

            if(trim($responseObject->params->durum) == 'ok' && intval(trim($responseObject->params->siparis_no)) > 0) {

                $orderNumber = intval(trim($responseObject->params->siparis_no));
                $commission = preg_replace('/[^0-9\.]/', '', trim($responseObject->params->komisyon));

                $package = collect($config['package'])->firstWhere('price', intval(preg_replace('/[^0-9]/', '', $responseObject->params->tutar)));
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
                    'Maxicard',
                    (string) Str::uuid(),
                    'true',
                    $package['price'],
                    $package['value'],
                    "User:{$user->username} purchased Silk for {$package['price']} using Maxicard. Order Number: {$orderNumber}.",
                    $user->jid,
                    $request->ip()
                );

                return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
            }
        }

        return back()->withErrors(['maxicard' => "Payment failed: {$responseObject->params->durum}"])->withInput();
    }

    public function processHipocard(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'password' => 'required',
        ]);

        $config = config('donate.hipocard');

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

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['success']) && $responseData['success'] === true) {

                $package = collect($config['package'])->firstWhere('price', intval($responseData['data']['total_sales']));
                if (!$package) {
                    return back()->withErrors(['hipocard' => 'Invalid package price.'])->withInput();
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
            }
        }

        return back()->withErrors(['hipocard' => "Payment failed: {$response->json()['message']}"])->withInput();
    }

    public function processHipopay(Request $request)
    {
        $request->validate([
            'price' => 'required|integer',
        ]);

        $config = config('donate.hipopay');
        $package = collect($config['package'])->firstWhere('price', $request->input('price'));
        if (!$package) {
            return back()->withErrors(['hipopay' => 'Invalid package selected.'])->withInput();
        }

        $user = Auth::user();
        $calculateHash = base64_encode(hash_hmac('sha256', $user->jid.$user->email.$user->username.$config['api-key'], $config['secret-key'], true));

        $payload = [
            'api_key' => $config['api-key'],
            'user_id' => $user->jid,
            'username' => $user->username,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'hash' => $calculateHash,
            'pro' => true,
            "product" => [
                'name' => $package['name'],
                'price' => $package['price'] * 100,
                'reference_id' =>  (string) Str::uuid(),
                'commission_type' => $config['commission_type'],
            ],
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($config['url'], $payload);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['success']) && $responseData['success'] === true) {
                $paymentUrl = $responseData['data']['payment_url'] ?? null;

                if ($paymentUrl) {
                    return redirect()->away($paymentUrl);
                }
            }
        }

        return back()->withErrors(['hipopay' => "Failed to create payment session! {$response->json()['message']}"])->withInput();
    }

    public function callbackHipopay(Request $request)
    {
        $config = config('donate.hipopay');
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        if (isset($data['status']) && $data['status'] === 'success') {
            $amount = $data['amount'] / 100;
            $referenceId = $data['reference_id'] ?? null;

            $package = collect($config['package'])->firstWhere('price', $amount);
            if (!$package) {
                Log::error('Hipopay Callback: Invalid package price.', $data);
                return response()->json(['status' => 'error', 'message' => 'Invalid package price.'], 400);
            }

            $user = Auth::user();
            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            DonateLog::setDonateLog(
                'Hipopay',
                (string) Str::uuid(),
                'true',
                $amount,
                $package['value'],
                "User:{$user->username} purchased Silk for {$package['value']} using Hipopay. Reference ID: {$referenceId}.",
                $user->jid,
                $request->ip()
            );

            return response()->json(['status' => 'success', 'message' => 'Payment processed successfully.'], 200);
        }

        Log::error('Hipopay Callback: Payment failed or invalid.', $data);
        return response()->json(['status' => 'error', 'message' => 'Payment failed or invalid.'], 400);
    }
}
