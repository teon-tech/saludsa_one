<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'sale_details';

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
        'plan_id',
        'hospital_id',
        'price',
        'subscription_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sale_id' => 'integer',
        'plan_id' => 'integer',
        'hospital_id' => 'integer',
        'price' => 'float',
        'subscription_type' => 'string'
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

    public function planPrice()
    {
        return $this->belongsTo(PlanPrice::class, 'plan_price_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
