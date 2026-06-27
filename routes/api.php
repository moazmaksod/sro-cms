<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RankingController;
use App\Http\Controllers\API\PagesController;

Route::get('/ranking/player', [RankingController::class, 'player']);
Route::get('/ranking/guild', [RankingController::class, 'guild']);
Route::get('/ranking/unique', [RankingController::class, 'unique']);
Route::get('/ranking/level', [RankingController::class, 'level']);
Route::get('/ranking/fortress-player', [RankingController::class, 'fortress_player']);
Route::get('/ranking/fortress-guild', [RankingController::class, 'fortress_guild']);

Route::get('/news', [PagesController::class, 'news']);
Route::get('/download', [PagesController::class, 'download']);
Route::get('/timers', [PagesController::class, 'timers']);
Route::get('/uniques', [PagesController::class, 'uniques']);

Route::get('/online-counter', [PagesController::class, 'onlineCounter']);

Route::middleware('web')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgot_password']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
