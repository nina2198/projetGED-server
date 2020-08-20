<?php

namespace App\Models\Schema;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    protected $guarded = [];

    //Un schema a plusieurs type de dossiers
    public function foderTypes() {
        return $this->hasMany('App\Models\Folder\FolderType');
    }
}
