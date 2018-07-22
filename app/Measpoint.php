<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Measpoint extends Model
{
    public function sensor() {
        return $this->belongsTo('App\Sensor');
    }
}
