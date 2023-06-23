<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
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
})->name('home');

Route::controller(AuthController::class)->name('auth')->group(function() {
    Route::post('/login', 'login')->name('.login');
    Route::post('/register', 'store')->name('.store');
    Route::get('/logout', 'logout')->name('.logout');
});
