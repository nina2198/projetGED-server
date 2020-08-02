<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Service;

class Service extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'idService', 'nomService',
    ];

    //definition des relations 
    public function activities()
    {
        return $this->hasMany('App\Models\Activity\Activites');
    }
}
