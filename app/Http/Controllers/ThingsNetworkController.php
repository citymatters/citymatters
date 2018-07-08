<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThingsNetworkController extends Controller
{
    public function ttnCallback(Request $request)
    {
        dd($request);
    }
}
