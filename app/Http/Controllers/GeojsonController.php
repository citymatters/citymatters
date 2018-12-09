<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers;

use App\Measpoint;
use Illuminate\Http\Request;
use App\Helper\GeojsonHelper;

class GeojsonController extends Controller
{
    public function getMeaspoints(Request $request)
    {
        $latStart = (float) $request->input('latStart', 0.0);
        $lonStart = (float) $request->input('lonStart', 0.0);
        $latEnd = (float) $request->input('latEnd', 1000.0);
        $lonEnd = (float) $request->input('lonEnd', 1000.0);

        $startTime = (int) $request->input('startTime', 0);
        $endTime = (int) $request->input('endTime', 10000000000000);

        if ($latStart > $latEnd) {
            $a = $latStart;
            $latStart = $latEnd;
            $latEnd = $a;
        }

        if ($lonStart > $lonEnd) {
            $a = $lonStart;
            $lonStart = $lonEnd;
            $lonEnd = $a;
        }

        if ($startTime > $endTime) {
            $a = $startTime;
            $startTime = $endTime;
            $endTime = $a;
        }

        $measpoints = Measpoint::where('lat', '>=', $latStart)
            ->where('lat', '<=', $latEnd)
            ->where('lon', '>=', $lonStart)
            ->where('lon', '<=', $lonEnd)
            ->where('datetime', '>=', $startTime)
            ->where('datetime', '<=', $endTime)
            ->limit(1000)
            ->get();

        return response()->json(GeojsonHelper::measpointsToGeojson($measpoints));
    }
}
