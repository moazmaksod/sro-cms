<?php

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/download', [HomeController::class, 'download'])->name('download');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/pages/{slug}', [HomeController::class, 'show'])->name('pages.show');
