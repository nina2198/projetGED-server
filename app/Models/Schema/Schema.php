<?php

namespace App\Models\Schema;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'id', 'service_number',
    ];
}
