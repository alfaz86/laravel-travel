<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
], function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh')->middleware('jwt.verify');
    Route::post('me', 'me')->middleware('jwt.verify');
});

Route::group([
    'prefix' => 'booking',
    'controller' => BookingApiController::class,
    'middleware' => 'jwt.verify',
], function () {
    Route::post('create', 'create')->name('booking.create');
});