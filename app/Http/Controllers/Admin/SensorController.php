<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers\Admin;

use App\Sensor;
use App\Http\Controllers\Controller;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::paginate(25);

        return view('admin.sensors', [
            'sensors' => $sensors,
        ]);
    }

    public function sensor($uuid)
    {
        $sensor = Sensor::where('uuid', $uuid)->firstOrFail();

        return view('admin.sensor', [
            'sensor' => $sensor,
        ]);
    }
}
