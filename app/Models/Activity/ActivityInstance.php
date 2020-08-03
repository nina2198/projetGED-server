<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class ActivityInstance extends Model
{
    protected $guarded = [];

    public function getStatus($status)
    {
        return [
            'WAITING' =>'En attente pour traitement', 
            'HANGING' => 'EN Suppend', 
            'ENDING' => 'Deja traité', 
            'EXECUTION' => 'en cours de traitement',
        ][$status];
    }

    /**
     * Get the activity for this instance
     */
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity\Activity','activity_id');
    }

    //une instance d'activité est effectuée par l'admin du service
    public function user(){
        return $this->belongsTo('App\Models\Person\User') ;
    }
}
