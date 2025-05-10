<?php

namespace App\Http\Controllers;

use App\Services\DonateService;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    public function index()
    {
        $data = config('donate');
        return view('profile.donate.donate', compact('data'));
    }

    public function show($method, Request $request)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        return view('profile.donate.show', compact('config', 'method'));
    }

    public function process($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        $request->validate([
            'price' => 'required|numeric|min:0.01',
        ]);

        if (method_exists($donateService, "process" . ucfirst($method))) {
            return $donateService->{"process" . ucfirst($method)}($request);
        }

        return redirect()->back()->withErrors('Invalid payment method.');
    }

    public function callback($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        if (method_exists($donateService, "handle" . ucfirst($method) . "Callback")) {
            return $donateService->{"handle" . ucfirst($method) . "Callback"}($request);
        }

        return redirect()->back()->withErrors('Invalid payment method.');
    }
}
