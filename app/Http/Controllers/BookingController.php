<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Ticket;
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

    public function detailNumber(Request $request, $bookingNumber)
    {
        $booking = Booking::with(['tickets'])
            ->where('booking_number', $bookingNumber)
            ->first();

        if (!$booking) {
            return redirect('/')->with('alert', 'Tiket tidak ditemukan.');
        }

        return view('booking.detail.number', compact('booking'));
    }

    public function list()
    {
        return view('booking.list');
    }

    public function detailTicket(Request $request, $ticketNumber)
    {
        $ticket = Ticket::with(['booking.schedule'])
            ->where('ticket_number', $ticketNumber)->first();

        if (!$ticket) {
            return redirect('/')->with('alert', 'Tiket tidak ditemukan.');
        }

        $checkStreamSetting = env('APP_STREAM_SETTING', 'off');

        return view('booking.detail.ticket', compact('ticket', 'checkStreamSetting'));
    }
}
