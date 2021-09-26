<?php

namespace App\Models;

use App\Processes\CategoryProcess;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Class Provider
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string owner
 * @property string description
 * @property string category
 * @property string address
 * @property string country_code
 * @property string phone
 * @property string message
 * @property string code
 * @property string status
 * @property float qualification
 *
 * @property MorphMany images
 * @property HasOne user
 * @property HasMany stores
 */
class Provider extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    public $table = 'provider';

    protected $fillable = [
        'name',
        'owner',
        'description',
        'category',
        'address',
        'country_code',
        'phone',
        'message',
        'code',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'owner' => 'string',
        'description' => 'string',
        'category' => 'string',
        'address' => 'string',
        'country_code' => 'string',
        'phone' => 'string',
        'message' => 'string',
        'code' => 'string',
        'status' => 'string',
        'qualification' => 'float'
    ];

    /**
     * @return MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'entity');
    }

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'provider_id');
    }

    /**
     * @param $providerId
     * @return array
     */
    public function getCategories($providerId)
    {
        $categoryIds = Category::query()
            ->select('category.id')
            ->join('categories_by_product', 'categories_by_product.category_id', '=', 'category.id')
            ->join('product', 'product.id', '=', 'categories_by_product.product_id')
            ->where('product.provider_id', '=', $providerId)
            ->groupBy('category.id')
            ->get()
            ->pluck('id')
            ->toArray();
        $categoryProcess = new CategoryProcess(new CategoryRepository());
        $ids = [];
        foreach ($categoryIds as $categoryId) {
            $ids = array_merge($ids, Category::getParentId(Category::query()->find($categoryId)));
        }
        $categories = $categoryProcess->buildTreeCategory(1, null, $ids);
        return $categories;
    }
    /**
     * @return HasMany
     */
    public function stores()
    {
        return $this->hasMany(Store::class, 'provider_id')
                    ->where('status', '=', 'ACTIVE');
    }
    /**
     * @param $providerId
     * @return string
     */
    public function getImageProfile($providerId)
    {
            $image = Image::query()
            ->select('image.file_name')
            ->join('image_parameter', 'image_parameter.id', '=', 'image.image_parameter_id')
            ->where('image.entity_id', '=', $providerId)
            ->where('image_parameter.entity', '=', 'PROVIDER')   
            ->where('image_parameter.name', '=', 'profile')->first();
            if(!$image){
                return null;
            }else{
                $folderName = 'provider';
                $folder = "$folderName/{$providerId}";
                $fileName = $image->file_name;
                $url = Storage::disk('s3')->url("{$folder}/{$fileName}");
            return $url ;
            }
            
    }
}
