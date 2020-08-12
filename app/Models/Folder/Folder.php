<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    //Un dossier est d'un type de dossier precis 
    public function folderType() {
        return $this->belongsTo('App\Models\Folder\FolderType', 'folder_type_id') ;
    }

    //Un dossier a plusieurs fichiers
    public function files() {
        return $this->hasMany('App\Models\Folder\File');
    }

    //Un dossier a plusieurs instances d'activites
    public function activityInstances() {
        return $this->hasMany('App\Models\Activity\ActivityInstance');
    }
}
