<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FolderType extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function fileTypes() {
        return $this->hasMany('App\Models\Folder\FileType');
    }
}
