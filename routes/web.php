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
        'data' => ['message' => 'users transactions'],
        'meta' => ['version' => 1]
    ]);
})->name('home');

Route::controller(AuthController::class)->name('auth')->group(function() {
    Route::post('/login', 'login')->name('.login');
    Route::post('/register', 'store')->name('.store');
});
