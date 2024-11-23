<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Booking\CreateBookingRequest;
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
            $ticketNumber = $this->generateTicketNumber();

            // Create booking record
            $booking = Booking::create([
                'schedule_id' => $validatedData['schedule_id'],
                'user_id' => $validatedData['user_id'],
                'ticket_number' => $ticketNumber,
                'quantity' => $validatedData['quantity'],
                'total_price' => $validatedData['total_price'],
                'passenger_name' => $validatedData['passenger_name'],
                'passenger_phone' => $validatedData['passenger_phone'],
                'payment_status' => Booking::STATUS_PENDING,
            ]);

            // Commit transaction
            DB::commit();

            $data = [
                ...$booking->toArray(),
                'redirect' => route('booking.detail.me', ['ticketNumber' => $booking->ticket_number]),
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
    private function generateTicketNumber(): string
    {
        $randomString = Str::random(12);
        $timestamp = now()->timestamp;

        return "TICKET-$randomString-$timestamp";
    }
}
