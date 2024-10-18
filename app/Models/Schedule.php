<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'departure_time',
        'arrive_time',
        'available_seats',
        'price',
        'origin_id',
        'destination_id',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function origin()
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    public function getIntervalTime()
    {
        $departure = Carbon::createFromFormat('H:i:s', $this->departure_time);
        $arrive = Carbon::createFromFormat('H:i:s', $this->arrive_time);
        $diff = $departure->diff($arrive);
        
        if ($diff->h == 0 && $diff->i == 0) {
            return "0j 0m"; 
        }

        $result = [];
        if ($diff->h > 0) {
            $result[] = "{$diff->h}j";
        }
        if ($diff->i > 0 || $diff->h > 0) {
            $result[] = "{$diff->i}m";
        }

        return implode(' ', $result);
    }
}
