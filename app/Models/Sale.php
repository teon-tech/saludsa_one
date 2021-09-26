<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Events\GenerateSalesContractEvent;

class Sale extends Model
{
    use SoftDeletes;

    const STATUS_PAYMENT_PENDING = 'PENDING';
    const STATUS_PAYMENT_APPROVED = 'APPROVED';
    const STATUS_PAYMENT_REJECTED = 'REJECTED';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'sales';

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
        'customer_id',
        'customer',
        'billing_data_id',
        'billing_data',
        'province_id',
        'status_payment',
        'total',
        'contract_number',
        'info_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'customer_id' => 'integer',
        'customer'  => 'json',
        'billing_data_id'  => 'integer',
        'billing_data'  => 'json',
        'province_id'  => 'integer',
        'status_payment'  => 'string',
        'total'  => 'float',
        'contract_number' => 'string',
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
        'deleted_at'
    ];

    protected $dispatchesEvents = [
        'updated' => GenerateSalesContractEvent::class,
    ];

    public function customerData()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function billingData()
    {
        return $this->belongsTo(BillingData::class, 'billing_data_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class, 'sale_id');
    }
}
