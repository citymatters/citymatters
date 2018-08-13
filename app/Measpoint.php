<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Measpoint extends Model
{
    public function sensor()
    {
        return $this->belongsTo('App\Sensor');
    }
}
