<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public function owner()
    {
        if ($this->organization_id != 0) {
            return $this->belongsTo('App\Organization', 'organization_id');
        } else {
            return $this->belongsTo('App\User', 'user_id');
        }
    }
}
