<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Booking\CreateBookingRequest;
use App\Jobs\ExpireStatusBookingJob;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingApiController extends ApiController
{
    /**
     * Create a new booking.
     *
     * @param CreateBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateBookingRequest $request): JsonResponse
    {
        // Validated data from the request
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            // Generate unique ticket number
            $bookingNumber = $this->generateBookingNumber();

            // Create booking record
            $booking = Booking::create([
                'schedule_id' => $validatedData['schedule_id'],
                'user_id' => $validatedData['user_id'],
                'booking_number' => $bookingNumber,
                'quantity' => $validatedData['quantity'],
                'total_price' => $validatedData['total_price'],
                'passenger_name' => $validatedData['passenger_name'],
                'passenger_phone' => $validatedData['passenger_phone'],
                'payment_status' => Booking::STATUS_PENDING,
            ]);

            // Commit transaction
            DB::commit();
            // Call job to expire booking status
            ExpireStatusBookingJob::dispatch($booking)->delay(now()->addMinutes(15));

            $data = [
                ...$booking->toArray(),
                'redirect' => route('booking.detail.number', ['bookingNumber' => $booking->booking_number]),
            ];

            return $this->successResponse($data, 'Booking created successfully', 201);
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollBack();

            return $this->errorResponse('Failed to create booking: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate a unique ticket number.
     *
     * @return string
     */
    private function generateBookingNumber(): string
    {
        $randomString = Str::random(12);
        $timestamp = now()->timestamp;

        return "BID-$randomString-$timestamp";
    }

    /**
     * Get booking detail.
     *
     * @param Request $request
     * @param string $bookingNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)
            ->with('schedule')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($bookings, 'List of bookings');
    }
}
