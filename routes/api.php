<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->name('auth')->group(function() {
    Route::get('/logout', 'logout')->name('.logout');
});

Route::controller(UserController::class)->name('users')->group(function() {
    Route::get('/me', 'me')->name('.me');
});

Route::controller(TransactionController::class)->prefix('transactions')->name('transactions')->group(function() {
    Route::post('/', 'store')->name('.store');
});
