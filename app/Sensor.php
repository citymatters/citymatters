<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Sensor extends Model
{
    public function owner() {
        if($this->belongs_to_organization) {
            return $this->belongsTo('App\Organization', 'organization_id');
        } else {
            return $this->belongsTo('App\User', 'user_id');
        }
    }
}
