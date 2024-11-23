<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    public function detail(Request $request)
    {
        $cache_booking_ticket = Cache::get('booking_tickets_' . session()->getId(), []);

        if (empty($cache_booking_ticket)) {
            return redirect('/')->with('alert', 'Terjadi kesalahan.');
        }

        $schedule = Schedule::where('id', $cache_booking_ticket)->first();

        return view('booking.detail', compact('schedule'));
    }

    public function myBookingDetail(Request $request, $ticketNumber)
    {
        $booking = Booking::where('ticket_number', $ticketNumber)->first();

        if (!$booking) {
            return redirect('/')->with('alert', 'Tiket tidak ditemukan.');
        }

        return view('booking.detailme', compact('booking'));
    }
}
