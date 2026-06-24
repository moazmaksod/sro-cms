<?php

namespace App\Http\Controllers\InGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function survey(Request $request)
    {
        return view('in-game.survey.index');
    }
}
