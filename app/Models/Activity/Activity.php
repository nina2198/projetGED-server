<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Service ;

class Activity extends Model
{
    protected $guarded = [];

    //definition des relations
    public function service()
    {
        return $this->belongsTo('App\Models\Service\Service');
    }
}
