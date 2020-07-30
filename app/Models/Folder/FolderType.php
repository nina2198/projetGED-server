<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FolderType extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
