<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Sensor extends Model
{
    public function owner()
    {
        if ($this->organization_id) {
            return $this->belongsTo('App\Organization', 'organization_id');
        } else {
            return $this->belongsTo('App\User', 'user_id');
        }
    }
}
