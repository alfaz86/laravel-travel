<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Booking\CreateBookingRequest;
use Illuminate\Http\Request;

class BookingApiController extends ApiController
{
    public function create(CreateBookingRequest $request)
    {
        return $this->successResponse(
            $request->all(),
            'Booking created successfully',
            201
        );
    }
}
