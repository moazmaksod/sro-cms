<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FusionSimulatorController extends Controller
{
    public function index()
    {
        $defaultRates = [0.50, 0.40, 0.38, 0.26, 0.25, 0.22, 0.17, 0.15, 0.13, 0.08, 0.05]; // +0 → +11
        return view('fusionsimulator.fusion-simulator', compact('defaultRates'));
    }
}

