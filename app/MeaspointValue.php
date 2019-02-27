<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeaspointValue extends Model
{
    public function measpoint()
    {
        return $this->belongsTo('App\Measpoint');
    }
}
