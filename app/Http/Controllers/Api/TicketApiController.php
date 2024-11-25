<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketApiController extends ApiController
{
    public function streamStatus($ticketNumber)
    {
        $checkStreamSetting = env('APP_STREAM_SETTING', 'off');

        $startTime = time(); // Catat waktu mulai

        return response()->stream(function () use ($ticketNumber, $startTime, $checkStreamSetting) {
            while ($checkStreamSetting === 'on') {
                // Hentikan streaming setelah 1 detik
                if ((time() - $startTime) > 1) {
                    echo "data: " . json_encode(['message' => 'Stream closed by server']) . "\n\n";
                    break;
                }

                $ticket = cache()->remember("ticket_status_$ticketNumber", 1, function () use ($ticketNumber) {
                    return Ticket::where('ticket_number', $ticketNumber)->first();
                });

                if ($ticket) {
                    echo "data: " . json_encode(['status' => $ticket->status]) . "\n\n";
                } else {
                    echo "data: " . json_encode(['error' => 'Ticket not found']) . "\n\n";
                }

                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

}
