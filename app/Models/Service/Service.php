<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'nomService',
    ];

    //definition des relations 
    public function activity()
    {
        return $this->hasMany('App\Models\Activity\Activites');
    }
}
