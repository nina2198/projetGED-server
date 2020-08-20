<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    //Un type de fichier est dans un type de dossier precis 
    public function folderType() {
        return $this->belongsTo('App\Models\Folder\FolderType', 'folder_type_id') ;
    }

    //Un type de fichier a plusieurs fichiers
    public function files() {
        return $this->hasMany('App\Models\Folder\File');
    }
}
