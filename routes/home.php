<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [HomeController::class, 'news'])->name('news');
Route::get('/download', [HomeController::class, 'download'])->name('download');
Route::get('/post/{slug}', [HomeController::class, 'post'])->name('post.show');
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('page.show');
