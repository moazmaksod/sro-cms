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
        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        $config = config('donate.paypal');
        $accessToken = cache()->remember('paypal_access_token', 540, function () use ($config) {
            $response = Http::withBasicAuth($config['client_id'], $config['secret'])
                ->asForm()
                ->post($config['endpoint'] . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            return $response->successful() ? $response->json()['access_token'] : null;
        });

        if (!$accessToken) {
            return back()->withErrors(['paypal' => 'Unable to get PayPal access token.'])->withInput();
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $accessToken",
            'PayPal-Request-Id' => (string) Str::uuid(),
        ])->post($config['endpoint'] . '/v2/checkout/orders', [
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
        ]);

        if ($response->successful()) {
            $approvalLink = collect($response->json('links'))->firstWhere('rel', 'approve');
            if (!$approvalLink) {
                $approvalLink = collect($response->json('links'))->firstWhere('rel', 'payer-action');
            }

            if ($approvalLink && isset($approvalLink['href'])) {
                return redirect()->away($approvalLink['href']);
            }

            return back()->withErrors(['paypal' => 'Approval link not found in PayPal response.'])->withInput();
        }

        $error = $response->json('error_description') ?? 'Unknown error during order creation.';
        return back()->withErrors(['paypal' => "Payment creation failed: $error"])->withInput();
    }

    public function callbackPaypal(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $config = config('donate.paypal');
        $token = $request->get('token');

        $accessToken = cache()->remember('paypal_access_token', 540, function () use ($config) {
            $response = Http::withBasicAuth($config['client_id'], $config['secret'])
                ->asForm()
                ->post("{$config['endpoint']}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);
            return $response->successful() ? $response->json()['access_token'] : null;
        });

        if (!$accessToken) {
            return back()->withErrors(['paypal' => 'Unable to retrieve PayPal access token.'])->withInput();
        }

        $orderResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $accessToken",
        ])->get("{$config['endpoint']}/v2/checkout/orders/{$token}");

        if (!$orderResponse->successful()) {
            $error = $orderResponse->json()['error_description'] ?? 'Unable to retrieve order details.';
            return back()->withErrors(['paypal' => "Payment failed: $error."])->withInput();
        }

        $orderData = $orderResponse->json();
        $status = $orderData['status'] ?? '';

        if ($status === 'COMPLETED') {
            // Already captured
        } elseif ($status === 'APPROVED') {
            $captureResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken",
                'PayPal-Request-Id' => (string) Str::uuid(),
            ])->post("{$config['endpoint']}/v2/checkout/orders/{$token}/capture", new \stdClass());

            if (!$captureResponse->successful()) {
                $error = $captureResponse->json()['message'] ?? 'Failed to capture payment.';
                return back()->withErrors(['paypal' => "Payment failed: $error"])->withInput();
            }

            $orderData = $captureResponse->json();
            if (($orderData['status'] ?? '') !== 'COMPLETED') {
                return back()->withErrors(['paypal' => "Payment not completed. Status: {$orderData['status']}"])->withInput();
            }
        } else {
            return back()->withErrors(['paypal' => "Transaction status is '{$status}', not completed."])->withInput();
        }

        $paidAmount = $orderData['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? null;
        if (!$paidAmount) {
            return back()->withErrors(['paypal' => 'Unable to determine paid amount.'])->withInput();
        }

        $package = collect($config['package'])->firstWhere('price', $paidAmount);
        if (!$package) {
            return back()->withErrors(['paypal' => "Invalid package amount: \${$paidAmount}."])->withInput();
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
            "User: {$user->username} purchased {$package['name']} for \${$package['price']}.",
            $user->jid,
            $request->ip()
        );

        return redirect()->route('profile.donate')->with('success', 'Payment completed successfully!');
    }

    public function processStripe(Request $request)
    {
        $config = config('donate.stripe');
        if (!$config['enabled']) {
            return back()->withErrors(['stripe' => 'Stripe payments are currently disabled.'])->withInput();
        }
        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        try {
            $price = $request->input('price');
            $package = collect($config['package'])->firstWhere('price', $price);

            if (!$package) {
                return back()->withErrors(['stripe' => 'Invalid package selected.'])->withInput();
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $config['secret_key'],
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($config['endpoint'].'/v1/checkout/sessions', [
                'payment_method_types[]' => 'card',
                'line_items[0][price_data][currency]' => strtolower($config['currency']),
                'line_items[0][price_data][product_data][name]' => $package['name'],
                'line_items[0][price_data][unit_amount]' => $price * 100,
                'line_items[0][quantity]' => 1,
                'mode' => 'payment',
                'success_url' => route('callback', ['method' => 'stripe']) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('profile.donate'),
                'metadata[user_id]' => Auth::id(),
                'metadata[package_price]' => $price,
                'metadata[package_value]' => $package['value'],
                'metadata[package_name]' => $package['name'],
            ]);

            if ($response->successful()) {
                $session = $response->json();
                return redirect()->away($session['url']);
            }

            $errorMessage = $response->json('error.message') ?? 'Unknown Stripe error';
            return back()->withErrors(['stripe' => "Payment Failed: {$errorMessage}"])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Payment processing failed. Please try again.'])->withInput();
        }
    }

    public function callbackStripe(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $config = config('donate.stripe');
        $sessionId = $request->get('session_id');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $config['secret_key'],
            ])->get($config['endpoint']."/v1/checkout/sessions/{$sessionId}");

            if (!$response->successful()) {
                $errorMessage = $response->json('error.message') ?? 'Failed to retrieve session details';
                return back()->withErrors(['stripe' => "Payment Failed: {$errorMessage}"])->withInput();
            }

            $session = $response->json();

            if ($session['payment_status'] === 'paid') {
                $metadata = $session['metadata'];
                $packagePrice = $metadata['package_price'];
                $packageValue = $metadata['package_value'];
                $packageName = $metadata['package_name'];
                $userId = $metadata['user_id'];

                if ($userId != Auth::id()) {
                    return back()->withErrors(['stripe' => 'Invalid session user.'])->withInput();
                }

                $package = collect($config['package'])->firstWhere('price', $packagePrice);
                if (!$package) {
                    return back()->withErrors(['stripe' => 'Invalid package price.'])->withInput();
                }

                $user = Auth::user();
                if (config('global.server.version') === 'vSRO') {
                    SkSilk::setSkSilk($user->jid, 0, $package['value']);
                } else {
                    AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
                }

                DonateLog::setDonateLog(
                    'Stripe',
                    $session['id'],
                    'true',
                    $package['price'],
                    $package['value'],
                    "User:{$user->username} purchased {$package['name']} for \${$package['price']}.",
                    $user->jid,
                    $request->ip()
                );

                return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
            }

            return back()->withErrors(['stripe' => 'Payment was not completed successfully.'])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Payment processing failed. Please try again.'])->withInput();
        }
    }

    public function webhookStripe(Request $request)
    {
        $config = config('donate.stripe');
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        // TODO: Use Stripe's official webhook signature verification here for better security.
        // See: https://stripe.com/docs/webhooks/signatures
        // Prevent replay attacks by checking event ID (if present)
        $event = json_decode($payload, true);
        if (isset($event['id'])) {
            $cacheKey = 'stripe_event_' . $event['id'];
            if (cache()->has($cacheKey)) {
                \Log::warning('Stripe webhook replay detected', ['event_id' => $event['id']]);
                return response('Replay attack detected', 409);
            }
            cache()->put($cacheKey, true, 3600); // Store for 1 hour
        }
        if ($config['webhook_secret']) {
            $computedSignature = hash_hmac('sha256', $payload, $config['webhook_secret']);
            if (!hash_equals($computedSignature, $sigHeader)) {
                return response('Invalid signature', 400);
            }
        }

        try {
            if ($event['type'] === 'checkout.session.completed') {
                $session = $event['data']['object'];

                // Handle successful payment here if needed
                \Log::info('Stripe webhook: Payment completed', ['session_id' => $session['id']]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            \Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
            return response('Webhook error', 400);
        }
    }

    public function processSimplyeasier(Request $request)
    {
        $config = config('donate.simplyeasier');
        if (!$config['enabled']) {
            return back()->withErrors(['simplyeasier' => 'Simply Easier payments are currently disabled.'])->withInput();
        }
        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        $price = $request->input('price');
        $package = collect($config['package'])->firstWhere('price', $price);

        if (!$package) {
            return back()->withErrors(['simplyeasier' => 'Invalid package selected.'])->withInput();
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $config['api_key'],
                'Accept' => 'application/json',
            ])->post($config['endpoint'] . '/payments', [
                'amount' => $price,
                'currency' => $config['currency'],
                'description' => $package['name'],
                'metadata' => [
                    'user_id' => Auth::id(),
                    'package_value' => $package['value'],
                    'package_name' => $package['name'],
                ],
                'callback_url' => route('callback', ['method' => 'simplyeasier']),
                'success_url' => route('profile.donate'),
                'cancel_url' => route('profile.donate'),
            ]);

            if ($response->successful()) {
                return redirect()->away($response['payment_url']);
            }

            return back()->withErrors(['simplyeasier' => 'Payment initialization failed.'])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['simplyeasier' => 'Payment error: ' . $e->getMessage()])->withInput();
        }
    }

    public function callbackSimplyeasier(Request $request)
    {
        // Validate and process payment result
        $paymentId = $request->get('payment_id');

        // Fetch payment status from Simply Easier
        $config = config('donate.simplyeasier');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $config['api_key'],
        ])->get($config['endpoint'] . "/payments/{$paymentId}");

        if (!$response->successful() || $response['status'] !== 'paid') {
            return back()->withErrors(['simplyeasier' => 'Payment was not successful.']);
        }

        // Assume metadata is returned
        $metadata = $response['metadata'];
        $user = User::find($metadata['user_id']);

        if (!$user) {
            return back()->withErrors(['simplyeasier' => 'User not found.']);
        }

        $value = $metadata['package_value'];
        $name = $metadata['package_name'];
        $price = $response['amount'];

        if (config('global.server.version') === 'vSRO') {
            SkSilk::setSkSilk($user->jid, 0, $value);
        } else {
            AphChangedSilk::setChangedSilk($user->jid, 3, $value);
        }

        DonateLog::setDonateLog(
            'SimplyEasier',
            $paymentId,
            'true',
            $price,
            $value,
            "User:{$user->username} purchased {$name} for \${$price}.",
            $user->jid,
            $request->ip()
        );

        return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
    }

    public function processCoinPayments(Request $request)
    {
        $config = config('donate.coinpayments');
        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);
        $package = collect($config['package'])->firstWhere('price', $request->input('price'));
        if (!$package) {
            return back()->withErrors(['coinpayments' => 'Invalid package selected.'])->withInput();
        }
        $payload = [
            "currency" => $config['currency'],
            "clientId" => $config['client_id'],
            "invoiceId" => (string) Str::uuid(),
            "items" => [
                [
                    "name" => $package['name'],
                    "quantity" => [
                        "value" => 1,
                        "type" => 2
                    ],
                    "amount" => "{$package['price']}"
                ]
            ],
            "amount" => [
                "breakdown" => [
                    "subtotal" => "{$package['price']}",
                ],
                "total" => "{$package['price']}",
            ],
            "payment" => [
                "refundEmail" => Auth::user()->email
            ],
            "email" => Auth::user()->email,
        ];

        $apiUrl = $config['endpoint'] . '/api/v2/merchant/invoices';
        $currentDate = now()->setTimezone('UTC')->format('Y-m-d\TH:i:s');
        $signatureString = implode('', [chr(239), chr(187), chr(191), 'POST', $apiUrl, $config['client_id'], $currentDate, json_encode($payload)]);
        $signature = base64_encode(hash_hmac('sha256', $signatureString, $config['client_secret'], true));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-CoinPayments-Client' => $config['client_id'],
            'X-CoinPayments-Timestamp' => $currentDate,
            'X-CoinPayments-Signature' => $signature,
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            $result = $response->json();

            if (isset($result['invoices'][0]['checkoutLink'])) {
                $checkoutLink = $result['invoices'][0]['checkoutLink'];

                return redirect()->away($checkoutLink);
            }
        }

        $errorMsg = isset($result['result']['error']) ? 'Payment failed.' : 'Unknown error';
        return back()->withErrors(['coinpayments' => $errorMsg])->withInput();
    }

    public function callbackCoinPayments(Request $request)
    {
        $config = config('donate.coinpayments');
        $hmac = hash_hmac('sha512', file_get_contents('php://input'), $config['ipn_secret']);
        if ($request->header('HMAC') !== $hmac) {
            return response('Invalid HMAC signature', 400);
        }
        $data = $request->all();
        if (isset($data['status']) && ($data['status'] >= 100 || $data['status'] == 2)) {
            $user = User::find($data['custom']);
            $package = collect($config['package'])->firstWhere('price', $data['amount1']);
            if (!$user || !$package) {
                return response('Invalid user or package', 400);
            }

            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            DonateLog::setDonateLog(
                'CoinPayments',
                $data['txn_id'],
                'true',
                $data['amount1'],
                $package['value'],
                "User:{$user->username} purchased {$package['name']} using CoinPayments.",
                $user->jid,
                $request->ip()
            );

            return response('OK', 200);
        }

        return response('Payment not completed', 400);
    }

    public function processFawaterk(Request $request)
    {
        $config = config('donate.fawaterk');
        $user = Auth::user();
        $request->validate([
            'price' => 'required|numeric|min:5',
        ]);

        $package = collect($config['package'])->firstWhere('price', $request->input('price'));
        if (!$package) {
            return back()->withErrors(['fawaterk' => 'Invalid package selected.'])->withInput();
        }

        $invoiceData = [
            'cartTotal' => $package['price'],
            'currency' => $config['currency'],
            'customer' => [
                'first_name' => $user->username,
                'email' => $user->email,
            ],
            "redirectionUrls" => [
                "successUrl" => route('callback', ['method' => 'fawaterk', 'status' => 'success']),
                "failUrl" => route('callback', ['method' => 'fawaterk', 'status' => 'fail']),
                "pendingUrl" => route('callback', ['method' => 'fawaterk', 'status' => 'pending']),
            ],
            'cartItems' => [
                [
                    'name' => $package['name'],
                    'price' => $package['price'],
                    'quantity' => 1,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $config['key'],
        ])->post($config['endpoint'] . '/api/v2/createInvoiceLink', $invoiceData);

        if ($response->successful()) {
            $paymentUrl = $response['data']['url'] ?? null;

            if ($paymentUrl) {
                DonateLog::setDonateLog(
                    'Fawaterk',
                    $response['data']['invoiceId'],
                    'false',
                    $package['price'],
                    $package['value'],
                    "User:{$user->username} purchased {$package['name']} using Fawaterk.",
                    $user->jid,
                    $request->ip()
                );

                return redirect()->away($paymentUrl);
            }
        }

        $errorMsg = isset($response['message']) && !is_array($response['message']) ? 'Payment failed.' : 'An error occurred';
        return back()->withErrors(['fawaterk' => $errorMsg])->withInput();
    }

    public function callbackFawaterk(Request $request)
    {
        $config = config('donate.fawaterk');
        $status = $request->query('status');
        $invoice_id = $request->query('invoice_id');

        if ($status === 'success') {
            $transaction_id = DonateLog::where('transaction_id', $invoice_id)->where('status', 'false')->first();
            if (!$transaction_id) {
                return back()->withErrors(['fawaterk' => 'Invalid transaction ID.'])->withInput();
            }

            $package = collect($config['package'])->firstWhere('price', $transaction_id->amount);
            if (!$package) {
                return back()->withErrors(['fawaterk' => 'Invalid package price.'])->withInput();
            }

            $user = User::where('jid', $transaction_id->jid)->first();
            if (!$user) {
                return back()->withErrors(['fawaterk' => 'User not found'])->withInput();
            }

            if (config('global.server.version') === 'vSRO') {
                SkSilk::setSkSilk($user->jid, 0, $package['value']);
            } else {
                AphChangedSilk::setChangedSilk($user->jid, 3, $package['value']);
            }

            $transaction_id->update(['status' => 'true']);

            return redirect()->route('profile.donate')->with('success', 'Payment processed successfully!');
        } elseif ($status === 'fail') {
            return back()->withErrors(['fawaterk' => 'Payment failed. Please try again.'])->withInput();
        } elseif ($status === 'pending') {
            return back()->withErrors(['fawaterk' => 'Payment is pending. Please check back later.'])->withInput();
        }else {
            return back()->withErrors(['fawaterk' => 'Unknown payment status.'])->withInput();
        }
    }

    public function processMaxicard(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'password' => 'required|string',
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
            'code' => 'required|string',
            'password' => 'required|string',
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

        $errorMsg = isset($response['message']) ? 'Payment failed.' : 'An error occurred';
        return back()->withErrors(['hipocard' => $errorMsg])->withInput();
    }

    public function processHipopay(Request $request)
    {
        $config = config('donate.hipopay');
        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        $package = collect($config['package'])->firstWhere('price', $request->input('price'));
        if (!$package) {
            return back()->withErrors(['hipopay' => 'Invalid package selected.'])->withInput();
        }

        $user = Auth::user();
        $hash = base64_encode(hash_hmac('sha256',$user->jid.trim($user->email).$user->username.$config['key'],$config['secret'] ,true));

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
        $hash = base64_encode(hash_hmac('sha256',$data["transaction_id"].$data["user_id"].$data["email"].$data["name"].$data["status"].$config['key'],$config['secret'] ,true));

        if (!hash_equals($data['hash'], $hash)) {
            return response('Invalid Hash', 400);
        }

        /*
        $transaction_id = DonateLog::where('transaction_id', $data['transaction_id'])->where('status', 'true')->exists();
        if ($transaction_id) {
            return response('This transaction has already been processed successfully.', 409);
        }
        */

        if ($data['status'] === 'success') {
            $package = collect($config['package'])->firstWhere('price', intval($data['payment_total'] / 100));
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
