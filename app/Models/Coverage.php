<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;

/**
 * Class Coverage
 * @package App\Http\Models
 *
 * @property BelongsTo typeCoverage
 * @property MorphMany images
 * @property BelongsTo plan
 * @property int id
 * @property int type_coverage_id
 * @property int plan_id
 * @property string name
 * @property string description
 * @property string status
 */
class Coverage extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'coverage';

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
        'type_coverage_id',
        'name',
        'description',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'plan_id' => 'integer',
        'type_coverage_id' => 'integer',
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'string',
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
     public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    } 
    /**
     * @return BelongsTo
     */
    public function typeCoverage()
    {
        return $this->belongsTo(TypeCoverage::class, 'type_coverage_id');
    } 
}
