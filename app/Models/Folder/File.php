<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    //Un fichier est d'un type de fichier precis 
    public function fileType() {
        return $this->belongsTo('App\Models\Folder\FileType', 'file_type_id') ;
    }
}
