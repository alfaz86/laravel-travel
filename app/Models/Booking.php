<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCEL = 'cancel';
    const STATUS_REFUND = 'refund';
    const STATUS_EXPIRED = 'expired';
    const STATUS = [
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_CANCEL,
        self::STATUS_REFUND,
        self::STATUS_EXPIRED,
    ];

    const STATUS_OPTIONS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PAID => 'Paid',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_REFUND => 'Refund',
        self::STATUS_EXPIRED => 'Expired',
    ];

    protected $fillable = [
        'schedule_id',
        'user_id',
        'booking_number',
        'booking_date',
        'quantity',
        'total_price',
        'payment_status',
        'snap_token',
        'passenger_name',
        'passenger_phone',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
