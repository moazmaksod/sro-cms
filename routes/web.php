<?php

use App\Http\Controllers\DonateController;
use App\Http\Controllers\PageController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/lang/{locale}', function ($locale) {if (in_array($locale, array_keys(config('global.languages')))) { Setting::updateOrCreate(['key' => 'locale'], ['value' => $locale]); } return redirect()->back(); })->name('lang.switch');
Route::get('/callback/{method}', [DonateController::class, 'callback'])->name('callback');

Route::get('/post/{slug}', [PageController::class, 'post'])->name('pages.post.show');
Route::get('/page/{slug}', [PageController::class, 'page'])->name('pages.page.show');
Route::get('/timers', [PageController::class, 'timers'])->name('pages.timers');
Route::get('/uniques', [PageController::class, 'uniques'])->name('pages.uniques');
Route::get('/uniques-advanced', [PageController::class, 'uniques_advanced'])->name('pages.uniques-advanced');
Route::any('/fortress', [PageController::class, 'fortress'])->name('pages.fortress');
Route::any('/globals', [PageController::class, 'globals'])->name('pages.globals');
Route::get('/download', [PageController::class, 'download'])->name('pages.download');
Route::get('/gateway', [PageController::class, 'gateway'])->name('pages.gateway');

require __DIR__.'/ranking.php';
require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
