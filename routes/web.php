<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/qr-reader', function () {
    return view('qr-reader');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('auth.login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('auth.register');
});

Route::prefix('schedule')->group(function () {
    Route::get('/list', [ScheduleController::class, 'list'])->name('schedule.list');
    Route::post('/search', [ScheduleController::class, 'search'])->name('schedule.search');
    Route::post('/select-ticket', [ScheduleController::class, 'selectTicket'])
        ->name(name: 'schedule.select-ticket')
        ->middleware('jwt.verify');
});

Route::prefix('location')->group(function () {
    Route::get('/search', [LocationController::class, 'search'])->name('location.search');
});

Route::prefix('booking')->group(function () {
    Route::get('/list', [BookingController::class, 'list'])->name('booking.list.page');
    Route::get('/detail', [BookingController::class, 'detail'])->name('booking.detail');
    Route::get('/detail/{bookingNumber}', [BookingController::class, 'detailNumber'])->name('booking.detail.number');
    Route::get('/detail/ticket/{ticketNumber}', [BookingController::class, 'detailTicket'])->name('booking.detail.ticket');
});

// only for development
// scan route local mode
Route::get('/scan', function () {
    return view('scan');
});