<?php

use App\Enums\PermissionEnum;
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
    Route::get('/logout', 'logout')->middleware("permission:".PermissionEnum::AUTH_GET_LOGOUT);
});

Route::controller(UserController::class)->name('users')->group(function() {
    Route::get('/me', 'me')->middleware("permission:".PermissionEnum::USER_GET_ME);
});

Route::controller(TransactionController::class)->prefix('transactions')->name('transactions')->group(function() {
    Route::post('/', 'store')->middleware("permission:".PermissionEnum::TRANSACTION_POST_STORE);
});
