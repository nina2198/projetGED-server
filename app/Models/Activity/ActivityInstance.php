<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class ActivityInstance extends Model
{
    protected $guarded = [];
    protected $table = 'activity_instance';
    /**
     * Get the activity for this instance
     */
    public function activity() {
        return $this->belongsTo('App\Models\Activity\Activity', 'activity_id');
    }

    //une instance d'activité est effectuée par l'admin du service
    public function user() {
        return $this->belongsTo('App\Models\Person\User', 'user_id') ;
    }

    //une instance d'activité est effectuée sur un dossier precis
    public function folder() {
        return $this->belongsTo('App\Models\Folder\Folder', 'folder_id') ;
    }

    //une instance d'activité est effectuée dans un service precis
    public function service() {
        return $this->belongsTo('App\Models\Service\Service', 'service_id') ;
    }

}
