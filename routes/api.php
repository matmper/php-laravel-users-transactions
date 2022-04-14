<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {   
    return response()->json([
        'success' => true,
        'message' => 'users transactions',
        'data' => [
            'version' => 1
        ]
    ]);
})->withoutMiddleware('auth:api');

Route::controller(AuthController::class)->group(function() {
    Route::post('/login', 'login')->withoutMiddleware('auth:api');
    Route::post('/register', 'store')->withoutMiddleware('auth:api');
    Route::get('/logout', 'logout');
});

Route::controller(UserController::class)->prefix('users')->group(function() {
    Route::get('/me', 'me');
});
