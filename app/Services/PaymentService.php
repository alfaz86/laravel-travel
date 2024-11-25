<?php

namespace App\Services;

use App\Models\Booking;
use App\Traits\MidtransConfig;
use Midtrans\Snap;

class PaymentService
{
    use MidtransConfig;

    public function __construct(
        protected TicketService $ticketService
    )
    {
        $this->ticketService = $ticketService;
    }

    public function createPayment(Booking $booking): string
    {
        $this->setMidtransConfig();

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_number,
                'gross_amount' => $booking->total_price,
            ],
            'item_details' => [
                [
                    'id' => $booking->booking_number,
                    'price' => $booking->schedule->price,
                    'quantity' => $booking->quantity,
                    'name' => 'Tiket Bus ' . $booking->schedule->origin->name . ' - ' . $booking->schedule->destination->name,
                ],
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    public function handleCallback($payload): void
    {
        $this->setMidtransConfig();

        // Proses berdasarkan status transaksi
        if ($payload['transaction_status'] === 'capture' || $payload['transaction_status'] === 'settlement') {
            // Pembayaran berhasil
            $this->updateBookingStatus($payload['order_id'], Booking::STATUS_PAID);
            $this->ticketService->createTicket($payload['order_id']);
        } elseif ($payload['transaction_status'] === 'pending') {
            // Pembayaran menunggu
            $this->updateBookingStatus($payload['order_id'], Booking::STATUS_PENDING);
        } elseif ($payload['transaction_status'] === 'expire' || $payload['transaction_status'] === 'cancel') {
            // Pembayaran gagal atau dibatalkan
            $this->updateBookingStatus($payload['order_id'], Booking::STATUS_CANCEL);
        } else {
            throw new \Exception('Status transaksi tidak dikenali.');
        }
    }

    private function updateBookingStatus(string $orderId, string $status): void
    {
        $booking = Booking::where('booking_number', $orderId)->first();

        if (!$booking) {
            throw new \Exception('Booking tidak ditemukan.');
        }

        if ($status === Booking::STATUS_PAID || $status === Booking::STATUS_CANCEL) {
            $booking->snap_token = null;
        }

        $booking->payment_status = $status;
        $booking->save();
    }

}
