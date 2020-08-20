<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FolderType extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    //Un type de dossier est constitue de plusieurs types de fichiers fichiers
    public function fileTypes() {
        return $this->hasMany('App\Models\Folder\FileType');
    }

    //Un type de dossier a plusieurs dossiers
    public function folders() {
        return $this->hasMany('App\Models\Folder\Folders');
    }

    //Un type de dossier possede un schema precis precis 
    public function schema() {
        return $this->belongsTo('App\Models\Schema\Schema', 'schema_id') ;
    }
}
