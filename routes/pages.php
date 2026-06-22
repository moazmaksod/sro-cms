<?php

use App\Http\Controllers\Pages\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/download', [HomeController::class, 'download'])->name('download');
Route::get('/news', [HomeController::class, 'news'])->name('news');
Route::get('/news/{slug}', [HomeController::class, 'post'])->name('news.show');
Route::get('/pages/{slug}', [HomeController::class, 'page'])->name('pages.show');
