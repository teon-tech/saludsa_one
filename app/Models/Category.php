<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Category
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string description
 * @property int level
 * @property int weight
 * @property int parent_category_id
 * @property string status
 *
 * @property HasMany children
 * @property BelongsTo parent
 * @property MorphMany images
 */
class Category extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'category';

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
        'description',
        'level',
        'weight',
        'parent_category_id',
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
        'description' => 'string',
        'level' => 'integer',
        'weight' => 'integer',
        'parent_category_id' => 'integer',
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
        'delete_at'
    ];


    const STATUS_ACTIVE = 'ACTIVE';
    const MAX_LEVEL = 3;

    /**
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'entity');
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
    public function buildName(Category $category, $name = '')
    {
        if ($category->parent_category_id) {
            $name = $this->buildName($category->parent, $name);
        } else {
            return $category->name;
        }
        $returnName = $category->name;
        return "{$name}->{$returnName}";
    }

    public static function getParentId(Category $category, $ids = [])
    {
        if ($category->parent_category_id) {
            $ids = array_merge($ids, self::getParentId($category->parent, $ids));
        } else {
            return [$category->id];
        }
        $ids[] = $category->id;
        return $ids;
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

}