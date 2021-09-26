<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @package App\Http\Models
 *
 * @property BelongsTo region
 */
class Hospital extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'hospital';

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
        'id',
        'region_id',
        'name',
        'status',
        'keywords',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'region_id' => 'integer',
        'name' => 'string',
        'status' => 'string',
        'keywords' => 'string',
        'description' => 'string'
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
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function plan()
    {
        return $this->belongsToMany(Plan::class, 'plans_by_hospital',
            'hospital_id', 'plan_id');
    }

     /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->buildName($this);
    }

    /**
     * @param Category $category
     * @param string $name
     * @return string
     */
    public function buildName(Hospital $hospital, $name = '')
    {
        return "{$hospital->plan[0]->name}->{$hospital->name}";
    }
}
