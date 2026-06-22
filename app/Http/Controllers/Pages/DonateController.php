<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\SRO\Account\SkSilkBuyList;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Services\DonateService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function index()
    {
        $data = config('donate');
        return view('pages.donate.index', compact('data'));
    }

    public function show($method)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        if (!view()->exists("pages.donate.{$method}")) {
            return redirect()->back()->withErrors(['error' => 'View file for the payment method is missing.']);
        }

        return view("pages.donate.{$method}", ['data' => $config]);
    }

    public function process($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");
        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        $methodName = "process" . ucfirst($method);
        if (!method_exists($donateService, $methodName)) {
            return redirect()->back()->withErrors('Invalid payment method.');
        }

        return $donateService->$methodName($request);
    }

    public function callback($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");
        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        $methodName = "callback" . ucfirst($method);
        if (!method_exists($donateService, $methodName)) {
            return redirect()->back()->withErrors('Invalid payment method.');
        }

        return $donateService->$methodName($request);
    }

    public function webhook($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");
        if (!$config || !$config['enabled']) {
            return response('Payment method not found or disabled.', 403);
        }

        $methodName = "webhook" . ucfirst($method);
        if (!method_exists($donateService, $methodName)) {
            return response('Invalid payment method.', 403);
        }

        return $donateService->$methodName($request);
    }

    public function history(Request $request): View
    {
        $page = $request->get('page', 1);
        if (config('global.server.version') === 'vSRO') {
            $data = SkSilkBuyList::getSilkBuyList($request->user()->jid, 25, $page);
        } else {
            $data = AphChangedSilk::getSilkHistory($request->user()->jid, 25, $page);
        }

        return view('pages.donate.history', [
            'user' => $request->user(),
            'data' => $data,
        ]);
    }
}
