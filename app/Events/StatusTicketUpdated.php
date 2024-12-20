<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusTicketUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $ticketNumber;

    public function __construct($status, $ticketNumber)
    {
        $this->status = $status;
        $this->ticketNumber = $ticketNumber;
    }

    public function broadcastOn()
    {
        return new Channel('status-ticket-updates');
    }

    public function broadcastAs()
    {
        return 'StatusTicketUpdated';
    }
}
