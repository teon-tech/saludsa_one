<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province'; //Database table used by the model


    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}