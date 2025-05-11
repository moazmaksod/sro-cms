<?php

use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
Route::prefix('ranking')->name('ranking.')->group(function() {
    Route::any('/player', [RankingController::class, 'player'])->name('player');
    Route::any('/guild', [RankingController::class, 'guild'])->name('guild');
    Route::any('/unique', [RankingController::class, 'unique'])->name('unique');
    Route::any('/unique-monthly', [RankingController::class, 'unique_monthly'])->name('unique-monthly');
    Route::any('/honor', [RankingController::class, 'honor'])->name('honor');
    Route::any('/job', [RankingController::class, 'job'])->name('job');
    Route::any('/job-all', [RankingController::class, 'job_all'])->name('job-all');
    Route::any('/job-hunter', [RankingController::class, 'job_hunter'])->name('job-hunter');
    Route::any('/job-thieve', [RankingController::class, 'job_thieve'])->name('job-thieve');
    Route::any('/job-trader', [RankingController::class, 'job_trader'])->name('job-trader');
    Route::any('/fortress-player', [RankingController::class, 'fortress_player'])->name('fortress-player');
    Route::any('/fortress-guild', [RankingController::class, 'fortress_guild'])->name('fortress-guild');

    Route::get('/character/{name}', [RankingController::class, 'character_view'])->name('character.view');
    Route::get('/guild/{name}', [RankingController::class, 'guild_view'])->name('guild.view');
    Route::any('/guild-crest/{hex}', [RankingController::class, 'guild_crest'])->name('guild-crest');
});
