<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function() {
    Route::get('/login', [AuthController::class, 'twitchLogin'])->name('login');
    Route::get('/login/redirect', [AuthController::class, 'twitchRedirect']);
    Route::get('/checkAuth', function() {
        return response()->json(Auth::check());
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/login/discord', [AuthController::class, 'discordLogin']);
        Route::get('/login/discord/redirect', [AuthController::class, 'discordRedirect']);
    });
});
