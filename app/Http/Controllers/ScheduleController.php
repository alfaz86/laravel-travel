<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchScheduleRequest;
use App\Models\Location;
use App\Models\Schedule;
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
        $cache_schedules = Cache::get('schedules_' . session()->getId(), []);
        $carbon = $this->carbon;

        if (empty($cache_schedules)) {
            return redirect('/')->with('alert', 'Session expired, please search again');
        }

        $date = $carbon::parse($cache_schedules['date']);
        $dayOfWeek = $date->format('l');
        $schedules = Schedule::where('origin_id', $cache_schedules['origin'])
            ->where('destination_id', $cache_schedules['destination'])
            ->whereJsonContains('active_days', $dayOfWeek)
            ->get();

        $location = Location::select();
        $origin = Location::find($cache_schedules['origin']);
        $destination = Location::find($cache_schedules['destination']);

        return view('schedule.list', compact('date', 'schedules', 'carbon', 'origin', 'destination'));
    }

    public function search(SearchScheduleRequest $request)
    {
        $cacheData = $request->only([
            'origin',
            'destination',
            'passengers',
            'date',
        ]);
        
        Cache::put('schedules_' . session()->getId(), $cacheData, now()->addMinutes(30));

        return redirect()->route('schedule.list');
    }

    public function selectTicket(Request $request)
    {
        $scheduleIds = $request->input('schedule_ids');

        if (empty($scheduleIds)) {
            return redirect()->back()->with('error', 'Tidak ada tiket yang dipilih.');
        }

        $scheduleIds = explode(',', $request->input('schedule_ids'));

        Cache::put('booking_tickets_' . session()->getId(), $scheduleIds, now()->addMinutes(30));
        
        return response()->json($scheduleIds);
    }
}
