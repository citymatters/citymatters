<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers;

use App\Measpoint;
use Illuminate\Http\Request;

class MeaspointsController extends Controller
{
    public function add(Request $request)
    {
        $measpoint = new Measpoint();
        $measpoint->sensor = $request->json('sensor');
        $measpoint->lat = $request->json('lat');
        $measpoint->lon = $request->json('lon');
        $measpoint->datetime = $request->json('datetime');
        foreach ($request->json('data') as $data) {
            $type = $data['type'];
            $measpoint->$type = $data['value'];
        }
        $measpoint->save();

        return response()->json(['success' => true]);
    }

    public function measpointsByAreaAndTime($latStart, $lonStart, $latEnd, $lonEnd, $startTime, $endTime)
    {
        $latStart = (float) $latStart;
        $lonStart = (float) $lonStart;
        $latEnd = (float) $latEnd;
        $lonEnd = (float) $lonEnd;

        $startTime = (int) $startTime;
        $endTime = (int) $endTime;

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
            ->get();

        return response()->json($measpoints);
    }

    public function measpointsByArea($latStart, $lonStart, $latEnd, $lonEnd)
    {
        $latStart = (float) $latStart;
        $lonStart = (float) $lonStart;
        $latEnd = (float) $latEnd;
        $lonEnd = (float) $lonEnd;

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

        $measpoints = Measpoint::where('lat', '>=', $latStart)
            ->where('lat', '<=', $latEnd)
            ->where('lon', '>=', $lonStart)
            ->where('lon', '<=', $lonEnd)
            ->orderBy('created_at', 'DESC')
            ->limit(1000)
            ->get();

        return response()->json($measpoints);
    }
}
