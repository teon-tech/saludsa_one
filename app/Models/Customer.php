<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customer'; //Database table used by the model

    protected $fillable = [
        'name',
        'father_last_name',
        'mother_last_name',
        'document_type',
        'document',
        'gender',
        'email',
        'birth_date',
        'years_old',
        'civil_status',
        'external_uid',
        'phone',
        'province_id',
        'direction'
    ]; 

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}