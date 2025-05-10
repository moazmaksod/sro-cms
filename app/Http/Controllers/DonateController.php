<?php

namespace App\Http\Controllers;

use App\Services\DonateService;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    public function index()
    {
        $data = config('donate');
        return view('profile.donate.index', compact('data'));
    }

    public function show($method)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

        $viewPath = "profile.donate.{$method}";
        if (!view()->exists($viewPath)) {
            return redirect()->back()->withErrors(['error' => 'View file for the payment method is missing.']);
        }

        return view($viewPath, ['data' => $config]);
    }

    public function process($method, Request $request, DonateService $donateService)
    {
        $config = config("donate.{$method}");

        if (!$config || !$config['enabled']) {
            return redirect()->back()->withErrors('Payment method not found or disabled.');
        }

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

        if (method_exists($donateService, "callback" . ucfirst($method))) {
            return $donateService->{"callback" . ucfirst($method)}($request);
        }

        return redirect()->back()->withErrors('Invalid payment method.');
    }
}
