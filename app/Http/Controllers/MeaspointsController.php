<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers;

use App\Measpoint;
use App\MeaspointValue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeaspointsController extends Controller
{
    public function add(Request $request)
    {
        $measpoint = new Measpoint();
        $measpoint->sensor_id = $request->json('sensor');
        $measpoint->lat = $request->json('lat');
        $measpoint->lon = $request->json('lon');
        $measpoint->datetime = Carbon::createFromTimestamp($request->json('datetime'));
        $measpoint->save();

        foreach ($request->json('data') as $data) {
            $value = new MeaspointValue();
            $value->measpoint_id = $measpoint->id;
            $value->value = $data['value'];
            $value->unit = $data['unit'];
            $value->type = $data['type'];
            $value->save();
        }
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

        $startTime = Carbon::createFromTimestamp($startTime);
        $endTime = Carbon::createFromTimestamp($endTime);

        $measpoints = Measpoint::where('lat', '>=', $latStart)
            ->where('lat', '<=', $latEnd)
            ->where('lon', '>=', $lonStart)
            ->where('lon', '<=', $lonEnd)
            ->where('datetime', '>=', $startTime->toDateTimeString())
            ->where('datetime', '<=', $endTime->toDateTimeString())
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

    public function getAverageForLastXDays(Request $request)
    {
        $latStart = (float) $request->input('latStart', 0.0);
        $lonStart = (float) $request->input('lonStart', 0.0);
        $latEnd = (float) $request->input('latEnd', 1000.0);
        $lonEnd = (float) $request->input('lonEnd', 1000.0);

        $days = (int) $request->input('days', 0);

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

        $earliest = now()->timestamp - $days * 86400;
        $earliest = (int) $earliest;
        $measpoints = Measpoint::where('lat', '>=', $latStart)
            ->where('lat', '<=', $latEnd)
            ->where('lon', '>=', $lonStart)
            ->where('lon', '<=', $lonEnd)
            ->where('datetime', '>=', $earliest)
            ->get();

        $days = [];
        $values = [
            'pm2',
            'pm10',
            'ozone',
            'sulfurDioxide',
            'carbonMonoxide',
            'nitrogenDioxide',
            'humidity',
            'temperature',
        ];
        foreach ($measpoints as $measpoint) {
            $day = (int) (($measpoint->datetime - $earliest) / 86400);
            if (! array_key_exists($day, $days)) {
                $days[$day] = [];
                foreach ($values as $val) {
                    $days[$day][$val] = [
                        'count' => 0,
                        'total' => 0,
                    ];
                }
            }
            foreach ($values as $val) {
                switch ($val) {
                    case 'pm2':
                    case 'pm10':
                    case 'ozone':
                    case 'sulfurDioxide':
                    case 'carbbonMonoxide':
                    case 'nitrogenDioxide':
                    case 'humidity':
                        if ($measpoint->$val < 0) {
                            $measpoint->$val = 0;
                        }
                        break;
                }
                $days[$day][$val]['count']++;
                $days[$day][$val]['total'] += $measpoint->$val;
            }
        }
        foreach ($days as $key => $day) {
            foreach ($day as $measurement => $value) {
                $days[$key][$measurement] = round($value['total'] / $value['count'], 2);
            }
            $days[$key]['index'] = $key;
        }

        return response()->json($days);
    }
}
