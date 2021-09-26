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
class SubscriptionLog extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'subscription_logs';

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
        'subscription_id',
        'transaction_status',
        'total',
        'payment_date',
        'ticket_number',
        'approval_code',
        'transaction_code',
        'total_info',
        'info_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'subscription_id' => 'integer',
        'transaction_status'  => 'string',
        'total'  => 'float',
        'ticket_number'  => 'string',
        'approval_code'  => 'string',
        'transaction_code'  => 'string',
        'total_info'  => 'json' ,
        'info_user' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'payment_date'
    ];
}