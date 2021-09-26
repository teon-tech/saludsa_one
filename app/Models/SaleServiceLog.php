<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionLog
 * @package App\Models
 *
 * @property int id
 * @property string email
 */
class SaleServiceLog extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'sale_service_logs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_id',
        'url',
        'payload',
        'status_code',
        'response'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sale_id' => 'integer',
        'url' => 'string',
        'payload' => 'json',
        'status_code' => 'string',
        'response' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
