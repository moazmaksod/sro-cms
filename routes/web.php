<?php

use App\Http\Controllers\Account\DonateController;
use App\Http\Controllers\Account\VoteController;
use App\Http\Controllers\Pages\HomeController;

require __DIR__.'/pages.php';
require __DIR__.'/logs.php';
require __DIR__.'/ranking.php';
require __DIR__.'/ingame.php';
require __DIR__.'/auth.php';
require __DIR__.'/account.php';
require __DIR__.'/admin.php';

Route::get('/language/{locale}', [HomeController::class, 'locale'])->name('locale');
Route::match(['GET', 'POST'],'/callback/{method}', [DonateController::class, 'callback'])->name('callback');
Route::match(['GET', 'POST'],'/webhook/{method}', [DonateController::class, 'webhook'])->name('webhook');
Route::match(['GET', 'POST'],'/postback/{site}', [VoteController::class, 'postback'])->name('postback');
