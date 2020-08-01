<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class InstActivites extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'idActivity',
        'idUser',
        'status',
    ];

    public function getStatus($status)
    {
        return [
            'WAITING' =>'En attente pour traitement', 
            'HANGING' => 'EN Suppend', 
            'ENDING' => 'Deja traité', 
            'EXECUTION' => 'en cours de traitement',
        ][$status];
    }
}
