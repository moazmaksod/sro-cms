<?php

namespace App\Http\Controllers\InGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function ranking(Request $request)
    {
        return view('in-game.ranking.index');
    }
}
