<?php

namespace App\Http\Controllers\Api;

use App\Events\StatusTicketUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Qr\QrReaderRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class QrApiController extends ApiController
{
    public function qrReader(QrReaderRequest $request)
    {
        $ticket = Ticket::where('ticket_number', $request->ticket_number)->first();

        if (!$ticket) {
            return $this->errorResponse('Ticket not found', 404);
        }

        if ($ticket->status === Ticket::STATUS_USED) {
            return $this->errorResponse('Ticket already used', 400);
        }

        if ($ticket->status === Ticket::STATUS_EXPIRED || $ticket->status === Ticket::STATUS_CANCEL) {
            return $this->errorResponse('Ticket can no longer be used', 400);
        }

        $ticket->update([
            'status' => Ticket::STATUS_USED,
        ]);

        if (env('APP_PUSHER_SETTING', false)) {
            event(new StatusTicketUpdated(
                Ticket::STATUS_USED,
                $request->ticket_number
            ));
        }

        return $this->successResponse(
            $request->ticket_number,
            'Ticket successfully used',
            200
        );
    }
}
