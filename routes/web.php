<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\VoteController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonateController;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/lang/{locale}', function ($locale) {if (in_array($locale, array_keys(config('global.languages')))) { Setting::updateOrCreate(['key' => 'locale'], ['value' => $locale]); } return redirect()->back(); })->name('lang.switch');

Route::get('/download', [PageController::class, 'download'])->name('download');
Route::get('/post/{slug}', [PageController::class, 'post'])->name('pages.post.show');
Route::get('/page/{slug}', [PageController::class, 'page'])->name('pages.page.show');
Route::get('/timers', [PageController::class, 'timers'])->name('pages.timers');
Route::get('/uniques', [PageController::class, 'uniques'])->name('pages.uniques');
Route::get('/uniques-advanced', [PageController::class, 'uniquesAdvanced'])->name('pages.uniques-advanced');
Route::get('/fortress', [PageController::class, 'fortress'])->name('pages.fortress');
Route::get('/globals', [PageController::class, 'globals'])->name('pages.globals');
//Route::get('/gateway', [PageController::class, 'gateway'])->name('pages.gateway');

Route::any('/callback/{method}', [DonateController::class, 'callback'])->name('callback');
Route::any('/webhook/{method}', [DonateController::class, 'webhook'])->name('webhook');
Route::any('/postback', [VoteController::class, 'postback'])->name('postback');

require __DIR__.'/ranking.php';
require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
