<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireStatusBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle()
    {
        $expiredTime = $this->booking->created_at->addMinutes(15);
        if ($this->booking->payment_status === Booking::STATUS_PENDING && now() >= $expiredTime) {
            $this->booking->update([
                'payment_status' => Booking::STATUS_EXPIRED,
                'snap_token' => null,
            ]);
            \Log::info('Booking status expired', ['booking' => $this->booking->toArray()]);
        }
    }
}
