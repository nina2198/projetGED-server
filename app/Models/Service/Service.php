<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\Activity\Activity;

class Service extends Model
{
    protected $guarded = [];
    
    //definition des relations 
    public function activities()
    {
        return $this->hasMany('App\Models\Activity\Activity');
    }

    public function users()
    {
        return $this->hasMany('App\Models\Person\User');
    }

    //Un service est implique dans plusieurs instances d'activite
    public function activityInstances() {
        return $this->hasMany('App\Models\Activity\ActivityInstance');
    }
}
