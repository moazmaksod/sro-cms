<?php

use App\Http\Controllers\Pages\LogsController;
use Illuminate\Support\Facades\Route;

Route::get('/logs', [LogsController::class, 'index'])->name('logs');
Route::prefix('logs')->name('logs.')->group(function() {
    Route::get('/schedule', [LogsController::class, 'schedule'])->name('schedule');
    Route::get('/unique', [LogsController::class, 'unique'])->name('unique');
    Route::get('/unique-advanced', [LogsController::class, 'uniqueAdvanced'])->name('unique-advanced');
    Route::get('/fortress', [LogsController::class, 'fortress'])->name('fortress');
    Route::get('/global', [LogsController::class, 'global'])->name('global');
    Route::get('/plus', [LogsController::class, 'plus'])->name('plus');
    Route::get('/drop', [LogsController::class, 'drop'])->name('drop');
    Route::get('/pvp', [LogsController::class, 'pvp'])->name('pvp');
    Route::get('/job', [LogsController::class, 'job'])->name('job');
});
