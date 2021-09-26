<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Http\Models
 *
 * @property BelongsTo typePlan
 * @property MorphMany images
 * @property BelongsToMany coverage
 * @property int id
 * @property int type_plan_id
 * @property string name
 * @property string code
 * @property string description
 * @property string isComparative
 * @property int weight
 * @property string status
 */
class Plan extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'plan';

    const ONE_PRODUCT = 'ONE';
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
        'code',
        'type_plan_id',
        'name',
        'subtitle',
        'default',
        'status',
        'keywords',
        'color_primary',
        'color_secondary',
        'description',
        'weight',
        'isComparative',
        'product_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type_plan_id' => 'integer',
        'id' => 'integer',
        'name' => 'string',
        'subtitle' => 'string',
        'default' => 'string',
        'status' => 'string',
        'keywords' => 'string',
        'color_primary' => 'string',
        'color_secondary' => 'string',
        'description' => 'string',
        'weight' => 'int',
        'isComparative' => 'string',
        'product_id' => 'integer'
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


    /**
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'entity');
    }

    /**
     * @return BelongsTo
     */
    public function typePlan()
    {
        return $this->belongsTo(TypePlan::class, 'type_plan_id');
    }
/**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    /**
     * @return HasMany
     */
    public function coverages()
    {
        return $this->hasMany(Coverage::class, 'plan_id');
    }

    /**
     * @return HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'plan_id');
    }

    /**
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany(FrequentQuestion::class, 'plan_id');
    }


    /**
     * @return BelongsToMany
     */
    /* public function coverages()
    {
        return $this->belongsToMany(Coverage::class, 'id')
            ->wherePivotNull('deleted_at');
    } */

    /**
     * @return MorphMany
     */
    public function files()
    {
        return $this->morphMany(File::class, 'entity');
    }
}
