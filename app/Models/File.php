<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 * @package App\Models
 *
 * @property string file_name
 * @property int entity_id
 * @property string entity_type
 */
class File extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'file';

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
        'file_name',
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
        'file_name' => 'string',
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


    public function getFolderAttribute()
    {
        $folder = '';
        switch (true) {
            case ($this->product_id != null):
                $folder = $folder . 'products';
                break;
            case ($this->category_id != null):
                $folder = $folder . 'categories';
                break;
            case ($this->provider_id != null):
                $folder = $folder . 'providers';
                break;
            case ($this->publicity_id != null):
                $folder = $folder . 'publicity';
                break;
            case ($this->coupon_id != null):
                $folder = $folder . 'coupons';
            case ($this->coverage_id != null):
                $folder = $folder . 'coverages';
            case ($this->plan_id != null):
                $folder = $folder . 'plan';
                break;
        }
        return $folder;
    }


    /**
     * Generate url for file
     */
    public function getUrlAttribute()
    {
        $folderName = $this->entity()->getModel()->table;
        $folder = "$folderName/{$this->entity_id}";
        $fileName = $this->file_name;
        if(config('constants.logicFileSystem') == 's3'){
            $url = Storage::disk('s3')->url("{$folder}/{$fileName}");
        }else{
            $url = asset("uploads/{$folder}/{$fileName}");
        }
        return $url;
    }

    

    /**
     * Get all of the owning models.
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }
}