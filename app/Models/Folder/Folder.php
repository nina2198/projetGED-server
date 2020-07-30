<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
