<?php

use App\Http\Controllers\DonateController;
use App\Http\Controllers\PanelController;

Route::prefix('{locale}')->where(['locale' => implode('|', array_keys(config('global.languages')))])->group(function () {
    require __DIR__.'/home.php';
    require __DIR__.'/history.php';
    require __DIR__.'/ranking.php';
    require __DIR__.'/game.php';
    require __DIR__.'/auth.php';
    require __DIR__.'/profile.php';
});

require __DIR__.'/admin.php';

Route::get('/', function () {
    return redirect('/' . config('app.locale'));
});

Route::any('/callback/{method}', [DonateController::class, 'callback'])->name('callback');
Route::any('/webhook/{method}', [DonateController::class, 'webhook'])->name('webhook');
Route::any('/postback/{site}', [PanelController::class, 'postback'])->name('postback');
