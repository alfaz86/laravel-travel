<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ScheduleService
{
    public function getCachedSchedules(string $sessionId): array
    {
        $cache_schedules = Cache::get('schedules_' . $sessionId, []);
        if (empty($cache_schedules)) {
            throw new \Exception('Session expired, please search again');
        }
        return $cache_schedules;
    }

    public function calculateIntervalTimeToBooking(Carbon $carbon): string
    {
        return $carbon::now('Asia/Jakarta')->addHour()->format('H:i:s');
    }

    public function getDayOfWeek(string $date): string
    {
        return Carbon::parse($date)->format('l');
    }

    public function fetchSchedules(
        array $cache_schedules,
        string $intervalTimeToBooking,
        string $dayOfWeek,
        string $date
    ): array|Collection
    {
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        return Schedule::available($date)
            ->where('origin_id', $cache_schedules['origin'])
            ->where('destination_id', $cache_schedules['destination'])
            ->whereJsonContains('active_days', $dayOfWeek)
            ->when($today === $date, function ($query) use ($intervalTimeToBooking) {
                return $query->where('departure_time', '>', $intervalTimeToBooking);
            })
            ->get();
    }

    public function getLocationFromSchedule(array $cache_schedules): array
    {
        $origin = Location::find($cache_schedules['origin']);
        $destination = Location::find($cache_schedules['destination']);
        return compact('origin', 'destination');
    }

}
