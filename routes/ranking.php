<?php

use App\Http\Controllers\Pages\RankingController;
use Illuminate\Support\Facades\Route;

Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
Route::prefix('ranking')->name('ranking.')->group(function() {
    Route::get('/player', [RankingController::class, 'playerRanking'])->name('player');
    Route::get('/guild', [RankingController::class, 'guildRanking'])->name('guild');
    Route::get('/unique', [RankingController::class, 'uniqueRanking'])->name('unique');
    Route::get('/unique-monthly', [RankingController::class, 'uniqueMonthlyRanking'])->name('unique-monthly');
    Route::get('/honor', [RankingController::class, 'honorRanking'])->name('honor');
    Route::get('/job', [RankingController::class, 'jobRanking'])->name('job');
    Route::get('/job-all', [RankingController::class, 'jobAllRanking'])->name('job-all');
    Route::get('/job-hunter', [RankingController::class, 'jobHunterRanking'])->name('job-hunter');
    Route::get('/job-thieve', [RankingController::class, 'jobThieveRanking'])->name('job-thieve');
    Route::get('/job-trader', [RankingController::class, 'jobTraderRanking'])->name('job-trader');
    Route::get('/fortress-player', [RankingController::class, 'fortressPlayerRanking'])->name('fortress-player');
    Route::get('/fortress-guild', [RankingController::class, 'fortressGuildRanking'])->name('fortress-guild');
    Route::get('/pvp-kd', [RankingController::class, 'pvpKDRanking'])->name('pvp-kd');
    Route::get('/job-kd', [RankingController::class, 'jobKDRanking'])->name('job-kd');
    Route::get('/custom/{type}', [RankingController::class, 'customRanking'])->name('custom');

    Route::get('/character/{name}', [RankingController::class, 'characterView'])->where('name', '[\[\]a-zA-Z0-9_ ]{1,64}')->name('character.view');
    Route::get('/guild/{name}', [RankingController::class, 'guildView'])->where('name', '[\[\]a-zA-Z0-9_ ]{1,64}')->name('guild.view');
    Route::get('/guild/crest/{bin}', [RankingController::class, 'guildCrest'])->name('guild.crest');
});
