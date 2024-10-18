<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function search(SearchScheduleRequest $request)
    {
        $schedules = Schedule::where('origin_id', $request['origin'])
            ->where('destination_id', $request['destination'])
            ->get();

        return view('schedule.list', compact('schedules'));
    }
}
