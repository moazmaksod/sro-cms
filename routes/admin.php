<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/index', [AdminController::class, 'index'])->name('admin');

    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/donate-logs', [AdminController::class, 'donate_logs'])->name('donate.logs');

        //Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/clear-cache', [SettingController::class, 'clear_cache'])->name('settings.clear-cache');

        //Users
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/view', [UsersController::class, 'view'])->name('users.view');
        Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');

        Route::put('/users/{user}/email', [UsersController::class, 'updateEmail'])->name('users.update.email');
        Route::put('/users/{user}/password', [UsersController::class, 'updatePassword'])->name('users.update.password');

        Route::post('/users/{user}/block', [UsersController::class, 'block'])->name('users.block');
        Route::put('/users/{user}/unban', [UsersController::class, 'unban'])->name('users.unban');
        
        //News
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::get('/news/{news}/delete', [NewsController::class, 'delete'])->name('news.delete');
        Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

        //Downloads
        Route::get('/download', [DownloadController::class, 'index'])->name('download.index');
        Route::get('/download/create', [DownloadController::class, 'create'])->name('download.create');
        Route::post('/download', [DownloadController::class, 'store'])->name('download.store');
        Route::get('/download/{download}/edit', [DownloadController::class, 'edit'])->name('download.edit');
        Route::put('/download/{download}', [DownloadController::class, 'update'])->name('download.update');
        Route::get('/download/{download}/delete', [DownloadController::class, 'delete'])->name('download.delete');
        Route::delete('/download/{download}', [DownloadController::class, 'destroy'])->name('download.destroy');

        //Pages
        Route::get('/pages', [PagesController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PagesController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PagesController::class, 'store'])->name('pages.store');
        Route::get('/pages/{pages}/edit', [PagesController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{pages}', [PagesController::class, 'update'])->name('pages.update');
        Route::get('/pages/{pages}/delete', [PagesController::class, 'delete'])->name('pages.delete');
        Route::delete('/pages/{pages}', [PagesController::class, 'destroy'])->name('pages.destroy');

        //Voucher
        Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::post('vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/vouchers/{voucher}/destroy', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    });
});
