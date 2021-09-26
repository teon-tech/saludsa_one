<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * Class Subscription
 * @package App\Models
 *
 * @property int id
 * @property string email
 */
class Subscription extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'subscriptions';

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
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
        'token',
        'status_subscription',
        'subscription_id',
        'type',
        'total',
        'number_card',
        'bank_info',
        'total_info',
        'additional_info',
        'status',
        'reason_status',
        'info_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sale_id' => 'integer',
        'token' => 'string',
        'status_subscription' => 'string',
        'subscription_id' => 'string',
        'type' => 'string',
        'total' => 'float',
        'number_card' => 'string',
        'bank_info' => 'json',
        'total_info' => 'json',
        'additional_info' => 'json',
        'status' => 'string',
        'reason_status' => 'string',
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
        'last_payment_at',
        'next_payment_at',
        'start_date'
    ];

    public function subscriptionLog()
    {
        return $this->hasMany(SubscriptionLog::class, 'subscription_id')
        ->orderBy('created_at' , 'DESC');
    }
}