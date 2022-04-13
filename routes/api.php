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

Route::controller(AuthController::class)->prefix('auth')->withoutMiddleware('auth:api')->group(function() {
    Route::post('/', 'login');
    Route::post('/register', 'store');
    Route::get('/logout', 'logout');
});

Route::controller(UserController::class)->prefix('user')->group(function() {
    Route::get('/me', 'me');
});
