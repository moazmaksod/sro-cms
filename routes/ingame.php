<?php

use App\Http\Controllers\InGame\BannerController;
use App\Http\Controllers\InGame\FortressController;
use App\Http\Controllers\InGame\RankingController;
use App\Http\Controllers\InGame\SurveyController;
use App\Http\Controllers\InGame\WebmallController;
use Illuminate\Support\Facades\Route;

Route::prefix('game')->name('game.')->group(function() {
    Route::any('/webmall', [WebmallController::class, 'webmall'])->name('webmall');
    Route::any('/ranking', [RankingController::class, 'ranking'])->name('ranking');
    Route::any('/survey', [SurveyController::class, 'survey'])->name('survey');
    Route::any('/fortress', [FortressController::class, 'fortress'])->name('fortress');
    Route::any('/banner', [BannerController::class, 'banner'])->name('banner');
});
