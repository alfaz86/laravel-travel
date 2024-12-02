<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::view
Route::view('/qr-reader', 'qr-reader');
Route::view('/scan', 'scan'); // Only for development

Route::prefix('auth')->group(function () {
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/register', 'auth.register')->name('auth.register');
});

Route::prefix('schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/list', 'list')->name('schedule.list');
    Route::post('/search', 'search')->name('schedule.search');
    Route::post('/select-ticket', 'selectTicket')->name('schedule.select-ticket')->middleware('jwt.verify');
});

Route::prefix('location')->controller(LocationController::class)->group(function () {
    Route::get('/search', 'search')->name('location.search');
});

Route::prefix('booking')->controller(BookingController::class)->group(function () {
    Route::get('/list', 'list')->name('booking.list.page');
    Route::get('/detail', 'detail')->name('booking.detail');
    Route::get('/detail/{bookingNumber}', 'detailNumber')->name('booking.detail.number');
    Route::get('/detail/ticket/{ticketNumber}', 'detailTicket')->name('booking.detail.ticket');
});
