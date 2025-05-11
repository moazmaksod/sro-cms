<?php

namespace App\Services;

use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\User;
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
        if (!$accessToken || !is_string($accessToken)) {
            return back()->withErrors(['paypal' => 'Failed to retrieve PayPal access token.'])->withInput();
        }

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

        return back()->withErrors(['paypal' => "Payment Failed: {$response['error_description']}"])->withInput();
    }

    public function callbackPaypal(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $config = config('donate.paypal');
        $token = $request->get('token');
        $accessToken = $this->getPaypalAccessToken();
        if (!$accessToken || !is_string($accessToken)) {
            return back()->withErrors(['paypal' => 'Failed to retrieve PayPal access token.'])->withInput();
        }

        $captureResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => (string) Str::uuid(),
            'Authorization' => "Bearer $accessToken",
        ])->get($config['endpoint']."/v2/checkout/orders/{$token}");

        if ($captureResponse->successful()) {
            $responseData = $captureResponse->json();

            if ($responseData['status'] === 'COMPLETED' || $responseData['status'] === 'APPROVED') {
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
                    $responseData['id'],
                    'true',
                    $package['price'],
                    $package['value'],
                    "User:{$user->username} purchased {$package['name']} for \${$package['price']}.",
                    $user->jid,
                    $request->ip()
                );

                return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
            }
        }

        return back()->withErrors(['paypal' => "Payment Failed: {$captureResponse['error_description']}"])->withInput();
    }

    private function getPaypalAccessToken()
    {
        $config = config('donate.paypal');
        $accessToken = cache()->get('paypal_access_token');

        if (!$accessToken) {
            $authResponse = Http::withBasicAuth($config['client_id'], $config['secret'])
                ->asForm()
                ->post($config['endpoint'].'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($authResponse->failed()) {
                return back()->withErrors(['paypal' => "Failed to retrieve PayPal access token! {$authResponse->json()['error_description']}"])->withInput();
            }

            $accessToken = $authResponse->json()['access_token'];
            cache()->put('paypal_access_token', $accessToken, 540);
        }

        return $accessToken;
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
                    return back()->withErrors(['maxicard' => 'Invalid package price.'])->withInput();
                }

                $user = Auth::user();
                if (config('global.server.version') === 'vSRO') {
                    SkSilk::setSkSilk($user->jid, 0, $package['value']);
                } else {
                    AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
                }

                DonateLog::setDonateLog(
                    'Maxicard',
                    $orderNumber,
                    'true',
                    $package['price'],
                    $package['value'],
                    "User:{$user->username} purchased Silk for {$package['price']} using Maxicard.",
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

        $payload = [
            'epin_code' => $request->code,
            'epin_secret' => $request->password,
            'player_name' => Auth::user()->username,
            'used_ip' => $request->ip(),
        ];

        $config = config('donate.hipocard');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
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
                    uniqid().rand(100,999),
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

        return back()->withErrors(['hipocard' => "Payment failed: " .isset($response['message']) ? $response['message'] : 'An error occurred'])->withInput();
    }

    public function processHipopay(Request $request)
    {
        $config = config('donate.hipopay');

        $package = collect($config['package'])->firstWhere('price', $request->input('price'));
        if (!$package) {
            return back()->withErrors(['hipopay' => 'Invalid package selected.'])->withInput();
        }

        $user = Auth::user();
        $hash = base64_encode(hash_hmac('sha256', $user->jid . trim($user->email) . $user->username . $config['key'], $config['secret'], true));

        $payload = [
            'api_key' => $config['key'],
            'api_secret' => $config['secret'],
            'user_id' => $user->jid,
            'username' => $user->username,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'hash' => $hash,
            'pro' => true,
            "product" => [
                'name' => $package['name'],
                'price' => $package['price'] * 100,
                'reference_id' =>  uniqid().rand(100,999),
                'commission_type' => $config['commission_type'],
            ],
        ];

        $response = Http::asForm()->post($config['url'], $payload);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($response['success']) && $responseData['success'] === true) {
                $paymentUrl = $responseData['data']['payment_url'] ?? null;

                if ($paymentUrl) {
                    return redirect()->away($paymentUrl);
                }
            }
        }

        return back()->withErrors(['hipopay' => "Payment Failed: " .(isset($response['message']) ? $response['message'] : 'An error occurred')])->withInput();
    }

    public function callbackHipopay(Request $request)
    {
        $config = config('donate.hipopay');
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);
        if (!$data) {
            return response('Invalid payload', 400);
        }

        $user = User::where('jid', $data['user_id'])->first();
        if (!$user) {
            return response('User not found', 404);
        }

        $hash = base64_encode(hash_hmac('sha256',$user->jid . trim($user->email) . $user->username . $config['key'], $config['secret'], true));

        if (!hash_equals($data['hash'], $hash)) {
            return response('Invalid Hash', 400);
        }

        $transaction_id = DonateLog::where('transaction_id', $data['transaction_id'])->where('status', 'true')->first();
        if ($transaction_id) {
            return response('This transaction has already been processed successfully.', 409);
        }

        if ($data['status'] === 'success') {
            $package = collect($config['package'])->firstWhere('price', intval($data['payment_total']));
            if (!$package) {
                return response('Invalid package price', 422);
            }

            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            DonateLog::setDonateLog(
                'HipoPay',
                $data['transaction_id'],
                'true',
                $package['price'],
                $package['value'],
                "User:{$user->username} purchased Silk for {$package['price']} using HipoPay.",
                $user->jid,
                $request->ip()
            );

            return response('OK', 200);
        }

        return response('Payment not successful', 422);
    }
}
