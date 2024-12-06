<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\QrApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::middleware('jwt.verify')->group(function () {
        Route::post('refresh', 'refresh');
        Route::post('me', 'me');
    });
});

Route::prefix('booking')->controller(BookingApiController::class)
    ->middleware('jwt.verify')
    ->group(function () {
        Route::post('create', 'create')->name('booking.create');
        Route::get('list', 'list')->name('booking.list');
    });

Route::prefix('payment')->controller(PaymentApiController::class)->group(function () {
    Route::middleware('jwt.verify')->group(function () {
        Route::post('pay/{bookingNumber}', 'pay')->name('payment.pay');
        Route::post('cancel/{bookingNumber}', 'cancel')->name('payment.cancel');
    });
    Route::post('callback', 'callback')->name('payment.callback')->withoutMiddleware('jwt.verify');
    Route::get('redirect', function () {
        return redirect()->route('booking.list.page');
    })->name('payment.redirect')->withoutMiddleware('jwt.verify');
});

Route::post('qr-reader', [QrApiController::class, 'qrReader'])
    ->middleware('jwt.verify')
    ->name('qr.reader');
