<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Http\Models
 *
 * @property int id
 * @property int provider_id
 * @property int customer_id
 * @property float subtotal
 * @property float tax
 * @property float tax_calculated
 * @property float total
 * @property string payment_status
 * @property string status
 * @property int qualification
 * @property string comment
 */
class Order extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'order';

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
        'provider_id',
        'customer_id',
        'subtotal',
        'tax',
        'tax_calculated',
        'total',
        'payment_status',
        'status',
        'qualification',
        'comment',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'provider_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'tax_calculated' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_status' => 'string',
        'status' => 'string',
        'qualification' => 'integer',
        'comment' => 'string',
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

    const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_INCOMPLETE = 'INCOMPLETE';
    const STATUS_INACTIVE = 'INACTIVE';

    const PAYMENT_STATUS_PAID = 'PAID';
    const PAYMENT_STATUS_PENDING = 'PENDING';
    const PAYMENT_STATUS_REJECTED = 'REJECTED';

    const  translate_payment_status = [
        'PENDING' => 'Pendiente Pago',
        'PAID' => 'Pagado'
    ];
    const  translate_status = [
        self::STATUS_IN_PROGRESS => 'Por gestionar',
        self::STATUS_COMPLETE => 'Gestionado',
    ];

    /**
     * @return mixed
     */
    public function getTranslatePaymentStatusAttribute()
    {
        return self::translate_payment_status[$this->payment_status];
    }

    /**
     * @return mixed
     */
    public function getTranslateStatusAttribute()
    {
        return self::translate_status[$this->status];
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    /**
     * @return HasMany
     */
    public function details()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

}
