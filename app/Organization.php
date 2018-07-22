<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Organization extends Model
{
    public function sensors() {
        return $this->hasMany('App\Sensor', 'organization_id');
    }
}
