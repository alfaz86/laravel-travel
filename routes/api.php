<?php

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\QrApiController;
use App\Http\Controllers\Api\TicketApiController;
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
    Route::get('list', 'list')->name('booking.list');
});

Route::group([
    'prefix' => 'payment',
    'controller' => PaymentApiController::class,
    'middleware' => 'jwt.verify',
], function () {
    Route::post('pay/{bookingNumber}', 'pay')->name('payment.pay');
    Route::post('callback', 'callback')->name('payment.callback')->withoutMiddleware('jwt.verify');
    Route::get('redirect', 'redirect')->name('payment.redirect')->withoutMiddleware('jwt.verify');
});

Route::group([
    'controller' => QrApiController::class,
    'middleware' => 'jwt.verify',
], function () {
    Route::post('qr-reader', 'qrReader')->name('qr.reader');
});

Route::group([
    'controller' => TicketApiController::class,
    'middleware' => 'jwt.verify',
], function () {
    Route::get('ticket-status/{ticketNumber}', 'streamStatus')
        ->name('ticket.status')
        ->withoutMiddleware(['jwt.verify', 'role:dev,admin']);
});