<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Service ;

class Activites extends Model
{
   // protected $guarded = [];
    protected $fillable = [
        'idService', 'description', 
    ];

    //definition des relations
    public function service()
    {
        return $this->belongsTo('App\Models\Service\Service');
    }
}
