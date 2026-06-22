<?php

use App\Http\Controllers\Account\PanelController as AccountPanelController;
use App\Http\Controllers\Pages\DonateController as PagesDonateController;

Route::prefix('{locale}')->where(['locale' => implode('|', array_keys(config('global.languages')))])->group(function () {
    require __DIR__.'/pages.php';
    require __DIR__.'/logs.php';
    require __DIR__.'/ranking.php';
    require __DIR__.'/game.php';
    require __DIR__.'/auth.php';
    require __DIR__.'/account.php';
});

require __DIR__.'/admin.php';

Route::get('/', function () {
    return redirect('/' . config('app.locale'));
});

Route::any('/callback/{method}', [PagesDonateController::class, 'callback'])->name('callback');
Route::any('/webhook/{method}', [PagesDonateController::class, 'webhook'])->name('webhook');
Route::any('/postback/{site}', [AccountPanelController::class, 'postback'])->name('postback');
