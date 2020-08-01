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
}
