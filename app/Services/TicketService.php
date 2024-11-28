<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketService
{
    public function createTicket(string $bookingId): void
    {
        $booking = Booking::where('booking_number', $bookingId)->first();

        if (!$booking) {
            throw new \Exception('Booking not found');
        }

        $tickets = [];
        for ($n = 1; $n <= $booking->quantity; $n++) {
            $tickets[] = [
                'booking_id' => $booking->id,
                'ticket_number' => $this->generateTicketNumber($booking->booking_number, $n),
                'status' => Ticket::STATUS_NOT_USED,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Ticket::insert($tickets);
    }

    public function generateTicketNumber(string $bookingNumber, int $index): string
    {
        $uniqueIdentifier = explode('-', $bookingNumber)[1];
        $timestamp = now()->timestamp;

        return "TID-$uniqueIdentifier-$index-$timestamp";
    }
}
