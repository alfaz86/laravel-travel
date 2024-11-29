<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $changeSearch = $request->get('change-search', false);
        $cacheKey = 'schedules_' . session()->getId();

        $origin = null;
        $destination = null;
        $date = null;
        $passengers = 1;
        
        if ($changeSearch === "true") {
            $cachedSchedules = Cache::get($cacheKey, []);
            $origin = $cachedSchedules['origin'] ?? null;
            $destination = $cachedSchedules['destination'] ?? null;
            $date = $cachedSchedules['date'] ?? null;
            $passengers = $cachedSchedules['passengers'];
        }

        $originName = $origin ? Location::find($origin)->name ?? '-' : null;
        $destinationName = $destination ? Location::find($destination)->name ?? '-' : null;

        return view('index', compact('origin', 'destination', 'date', 'passengers', 'originName', 'destinationName', 'changeSearch'));
    }
}
