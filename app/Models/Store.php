<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Store
 * @package App\Models
 *
 * @property int id
 * @property int provider_id
 * @property int city_id
 * @property string name
 * @property string address
 * @property string status
 *
 * @property BelongsTo provider
 * @property BelongsTo city
 */
class Store extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'store';

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
        'name',
        'address',
        'provider_id',
        'city_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'provider_id' => 'integer',
        'city_id' => 'integer',
        'status' => 'string',
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
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    
    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

}