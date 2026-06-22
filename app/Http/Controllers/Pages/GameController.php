<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.game.index');
    }

    public function webmall(Request $request)
    {
        return view('pages.game.webmall');
    }

    public function ranking(Request $request)
    {
        return view('pages.game.ranking');
    }

    public function survey(Request $request)
    {
        return view('pages.game.survey');
    }

    public function fortress(Request $request)
    {
        return view('pages.game.fortress');
    }

    public function banner(Request $request)
    {
        return view('pages.game.banner');
    }
}
