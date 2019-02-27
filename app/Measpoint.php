<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measpoint extends Model
{
    public function sensor()
    {
        return $this->belongsTo('App\Sensor');
    }

    public function values()
    {
        return $this->hasMany('App\MeaspointValue');
    }
}
