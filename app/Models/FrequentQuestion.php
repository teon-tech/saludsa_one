<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FrequentQuestion
 * @package App\Http\Models
 *

 * @property BelongsTo plan
 * @property int id
 * @property int plan_id
 * @property string title
 * @property string description
 * @property int weight
 * @property string status
 */
class FrequentQuestion extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'frequent_questions';

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
        'title',
        'description',
        'weight',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'plan_id' => 'integer',
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'weight' => 'integer',
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
        'deleted_at',
    ];
    /**
     * @return BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

}
