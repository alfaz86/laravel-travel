<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('q', '');
        $limit = $request->input('limit', 10);
        $origin = $request->input('origin', false);

        $locations = Location::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->when($origin, function ($query, $origin) {
                return $query->where('id', '!=', $origin);
            })
            ->limit($limit)
            ->get();

        return response()->json([
            'items' => $locations,
            'total_count' => $locations->count(),
        ]);
    }
}
