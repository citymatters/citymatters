<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Measpoint extends Model
{
    public function csvSerialize()
    {
        return [
            'sensor' => $this->sensor,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'datetime' => $this->datetime,
            'temperature' => $this->temperature,
            'humidity' => $this->humidity,
            'pm2' => $this->pm2,
            'pm10' => $this->pm10,
        ];
    }
}
