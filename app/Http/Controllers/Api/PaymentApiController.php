<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentApiController extends ApiController
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Request $request, string $bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)->first();

        if (!$booking) {
            return $this->notFoundResponse('Booking tidak ditemukan');
        }

        if ($booking->payment_status === Booking::STATUS_PAID) {
            return $this->errorResponse('Transaksi sudah dibayar', 400);
        }

        if (!empty($booking->snap_token)) {
            return $this->successResponse(
                ['snapToken' => $booking->snap_token],
                'Snap token berhasil ditemukan'
            );
        }
        
        try {
            $snapToken = $this->paymentService->createPayment(
                $booking,
                $request['remainingTime']
            );
            $booking->update(['snap_token' => $snapToken]);

            return $this->successResponse(
                ['snapToken' => $snapToken],
                'Snap token berhasil dibuat'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal membuat snap token',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    public function callback(Request $request)
    {
        try {
            $this->paymentService->handleCallback($request->all());

            return $this->successResponse(
                null,
                'Callback berhasil diproses'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal memproses callback',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    public function redirect(Request $request)
    {
        $host = env('APP_URL');
        return redirect($host . '/booking/list');
    }
}
