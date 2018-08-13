<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThingsNetworkController extends Controller
{
    public function ttnCallback(Request $request)
    {
        dd($request);
    }
}
