<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Organization extends Model
{
    public function sensors() {
        return $this->hasMany('App\Sensor', 'organization_id');
    }

    public function members() {
        return $this->belongsToMany('App\User')->withPivot('is_admin');
    }

    public function admins() {
        return $this->belongsToMany('App\User')->wherePivot('is_admin', '=', true);
    }
}
