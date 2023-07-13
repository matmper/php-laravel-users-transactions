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

Route::pattern('id', '[0-9]+');
//Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::controller(AuthController::class)->group(function() {
    Route::get('/logout', 'logout')->middleware("permission:".PermissionEnum::AUTH_LOGOUT);
});

Route::controller(UserController::class)->group(function() {
    Route::get('/me', 'me')->middleware("permission:".PermissionEnum::USER_ME);
    Route::prefix('users')->group(function() {
        Route::get('/{id}', 'show')->middleware("permission:".PermissionEnum::USER_SHOW);
        Route::patch('/{id}', 'update')->middleware("permission:".PermissionEnum::USER_UPDATE);
    });
});

Route::controller(TransactionController::class)->prefix('transactions')->group(function() {
    Route::post('/', 'store')->middleware("permission:".PermissionEnum::TRANSACTION_STORE);
});
