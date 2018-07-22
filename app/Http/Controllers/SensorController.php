<?php

namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function adminIndex() {
        $sensors = Sensor::paginate(25);
        return view('admin.sensors', [
            'sensors' => $sensors
        ]);
    }
}
