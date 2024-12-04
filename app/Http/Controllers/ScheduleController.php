<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\SearchScheduleRequest;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected Carbon $carbon
    ) {
        $this->scheduleService = new ScheduleService();
        $this->carbon = new Carbon();
    }

    public function list(Request $request)
    {
        try {
            $cache_schedules = $this->scheduleService->getCachedSchedules(session()->getId());
        } catch (\Exception $e) {
            return redirect('/')->with('alert', $e->getMessage());
        }

        $carbon = $this->carbon;
        $date = $carbon::parse($cache_schedules['date']);

        $intervalTimeToBooking = $this->scheduleService->calculateIntervalTimeToBooking($carbon);
        $dayOfWeek = $this->scheduleService->getDayOfWeek($cache_schedules['date']);
        $schedules = $this->scheduleService->fetchSchedules(
            $cache_schedules,
            $intervalTimeToBooking,
            $dayOfWeek,
            $cache_schedules['date']
        );
        $locations = $this->scheduleService->getLocationFromSchedule($cache_schedules);

        $origin = $locations['origin'];
        $destination = $locations['destination'];
        $passengers = $cache_schedules['passengers'];

        return view('schedule.list', compact('date', 'schedules', 'carbon', 'origin', 'destination', 'passengers'));
    }

    public function search(SearchScheduleRequest $request)
    {
        Cache::put('schedules_' . session()->getId(), $request->validated(), now()->addMinutes(30));

        return redirect()->route('schedule.list');
    }

    public function selectTicket(Request $request)
    {
        $scheduleId = $request->input('schedule_id');

        if (empty($scheduleId)) {
            return redirect()->back()->with('error', 'Tidak ada tiket yang dipilih.');
        }

        Cache::put('booking_tickets_' . session()->getId(), $scheduleId, now()->addMinutes(30));

        return response()->json($scheduleId);
    }
}
