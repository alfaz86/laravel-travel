<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    public function detail(Request $request)
    {
        $cache_booking_tickets = Cache::get('booking_tickets_' . session()->getId(), []);

        if (empty($cache_booking_tickets)) {
            return redirect('/')->with('alert', 'Terjadi kesalahan.');
        }

        $schedules = Schedule::whereIn('id', $cache_booking_tickets)->get();

        return view('booking.detail', compact('schedules'));
    }
}
