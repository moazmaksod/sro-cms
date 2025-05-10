<?php

use App\Http\Controllers\DonateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', config('settings.register_confirm') ? 'verified' : 'throttle'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/edit', [ProfileController::class, 'update'])->name('update');
        Route::delete('/edit', [ProfileController::class, 'destroy'])->name('destroy');
        Route::post('/edit/redeem', [ProfileController::class, 'redeem'])->name('redeem');
        Route::post('/edit/passcode', [ProfileController::class, 'passcode'])->name('passcode');
        Route::get('/silk-history', [ProfileController::class, 'silk_history'])->name('silk-history');

        Route::get('/donate', [DonateController::class, 'index'])->name('donate');
        Route::get('/donate/{method}', [DonateController::class, 'show'])->name('donate.show');
        Route::post('/donate/{method}/process', [DonateController::class, 'process'])->name('donate.process');
    });
});
