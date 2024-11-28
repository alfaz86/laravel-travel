<?php

namespace App\Http\Controllers\Api;

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

        $ticket->update([
            'status' => Ticket::STATUS_USED,
        ]);

        return $this->successResponse(
            $request->ticket_number,
            'Ticket successfully used',
            200
        );
    }
}
