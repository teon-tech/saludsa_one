<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPrice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'plan_price';

    const STATUS_ACTIVE = 'ACTIVE';

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
        'plan_id',
        'hospital_id',
        'gender',
        'range_age_from',
        'range_age_to',
        'monthly_price',
        'annual_price',
        'label_discount',
        'base_value',
        'subtotal',
        'farm_insurance',
        'administrative_price',
        'discount_percentage',
        'annual_price_discount',
        'enable_discounted'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'plan_id' => 'integer',
        'hospital_id' => 'integer',
        'gender' => 'string',
        'range_age_from' => 'integer',
        'range_age_to' => 'integer',
        'monthly_price' => 'float',
        'annual_price' => 'float',
        'label_discount' => 'string',
        'base_value' => 'float',
        'subtotal' => 'float',
        'farm_insurance' => 'float',
        'administrative_price' => 'float',
        'discount_percentage' => 'float',
        'annual_price_discount' => 'float',
        'enable_discounted' => 'string'
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

    /**
     * @return BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * @return BelongsTo
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

}