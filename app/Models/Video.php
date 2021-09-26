<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Video
 * @package App\Models
 *
 * @property string url
 * @property int entity_id
 * @property string entity_type
 */
class Video extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'video';

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
        'url',
        'entity_id',
        'entity_type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'url' => 'string',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'delete_at'
    ];

    /**
     * Get all of the owning models.
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }


    
}