<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchScheduleRequest;
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
            return redirect('/')->with('alert', 'No schedules found.');
        }

        $date = $carbon::parse($cache_schedules['date']);
        $schedules = Schedule::where('origin_id', $cache_schedules['origin'])
            ->where('destination_id', $cache_schedules['destination'])
            ->get();

        return view('schedule.list', compact('date', 'schedules', 'carbon'));
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
}
