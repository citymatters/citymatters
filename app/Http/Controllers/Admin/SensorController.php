<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index() {
        $sensors = Sensor::paginate(25);
        return view('admin.sensors', [
            'sensors' => $sensors
        ]);
    }

    public function sensor($uuid) {
        $sensor = Sensor::where('uuid', $uuid)->firstOrFail();
        return view('admin.sensor', [
            'sensor' => $sensor,
        ]);
    }
}
