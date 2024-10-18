<?php

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

Route::prefix('schedule')->group(function () {
    Route::get('/list', [ScheduleController::class, 'list'])->name('schedule.list');
    Route::post('/search', [ScheduleController::class, 'search'])->name('schedule.search');
    Route::post('/select-ticket/{schedule}', [ScheduleController::class, 'selectTicket'])->name('schedule.select-ticket');
});

Route::prefix('location')->group(function () {
    Route::get('/search', [LocationController::class, 'search'])->name('location.search');
});