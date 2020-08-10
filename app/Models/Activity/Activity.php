<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Service ;

class Activity extends Model
{
    protected $guarded = [];
    /**
     * definition des relations
     */

     //une activitté est creer pour un service
    public function service()
    {
        return $this->belongsTo('App\Models\Service\Service', 'service_id');
    }

    /**
     * Get the instances for the activity
     */
    public function activitiesInstances()
    {
        return $this->hasMany('App\Models\Activity\ActivityInstance');
    }
}
