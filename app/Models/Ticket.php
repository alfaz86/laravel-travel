<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    const STATUS_NOT_USED = 'not_used';
    const STATUS_USED = 'used';
    const STATUS_CANCEL = 'cancel';
    const STATUS_EXPIRED = 'expired';
    const STATUS = [
        self::STATUS_NOT_USED,
        self::STATUS_USED,
        self::STATUS_CANCEL,
        self::STATUS_EXPIRED,
    ];

    const STATUS_OPTIONS = [
        self::STATUS_NOT_USED => 'Not Used',
        self::STATUS_USED => 'Used',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_EXPIRED => 'Expired',
    ];

    protected $fillable = [
        'booking_id',
        'ticket_number',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
