<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class SchemaActivites extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'idSchemaActivite', 'idActivite', 'ordrePlacement',
    ];
}
