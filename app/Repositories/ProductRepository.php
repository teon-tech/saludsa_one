<?php


namespace App\Repositories;


use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ProductRepository
{

    /**
     * @param array $params
     * @param null $categoryIds
     * @return LengthAwarePaginator
     */
    public function findAllBy($params = [], $categoryIds = null)
    {
        $size = isset($params['size']) && $params['size'] ? $params['size'] : 10;

        $query = Product::query();
        $query->with(['provider', 'images', 'categories']);
        $query->select('product.*');
        $query->join('provider', 'provider.id', '=', 'product.provider_id');
        $query->leftJoin('categories_by_product', 'categories_by_product.product_id', '=', 'product.id');
        $query->where('product.status', Config::get('constants.active_status'));
        $query->where('provider.status', Config::get('constants.active_status'));

        if (isset($params['query']) && !in_array($params['query'], [null, ''])) {
            $searchValue = $params['query'];
            $query->where(function ($subQuery) use ($searchValue) {
                $subQuery->orWhere('product.name', 'like', "%$searchValue%");
                $subQuery->orWhere('product.code', 'like', "%$searchValue%");
            });
        }
        if (isset($params['priceRange']) && !in_array($params['priceRange'], [null, ''])) {
            $rangePrice = explode('-', $params['priceRange']);
            $priceFrom = $rangePrice[0] ?? null;
            $priceTo = $rangePrice[1] ?? null;
            if ($priceFrom != null && $priceTo != null) {
                $query->whereBetween('product.price', [(float)$priceFrom, (float)$priceTo]);
            }
        }

        if ($categoryIds != null) {
            $query->whereIn('categories_by_product.category_id', $categoryIds);
        }
        if (isset($params['providerId']) && !in_array($params['providerId'], [null, ''])) {
            $providerId = $params['providerId'];
            $query->where('product.provider_id', $providerId);
        }
        if (isset($params['providerCode']) && !in_array($params['providerCode'], [null, ''])) {
            $providerCode = $params['providerCode'];
            $query->where('provider.code', $providerCode);
        }

        $query->groupBy('product.id');

        return $query->paginate($size);
    }

    /**
     * @param $value
     * @param string $attr
     * @return Builder|Model|object|null
     */
    public function findBy($value, $attr = 'id')
    {
        return Product::query()
            ->where($attr, $value)
            ->first();
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function searchSuggested($params = [])
    {
        $size = isset($params['size']) && $params['size'] ? $params['size'] : 10;

        $query = Product::query();
        $query->with(['provider', 'images', 'categories']);
        $query->select('product.*');
        $query->join('provider', 'provider.id', '=', 'product.provider_id');
        $query->leftJoin('categories_by_product', 'categories_by_product.product_id', '=', 'product.id');
        $query->where('product.status', Config::get('constants.active_status'));
        $query->where('provider.status', Config::get('constants.active_status'));
        $query->where('product.count_order', '>', 0);
        $query->orderBy('product.count_order', 'desc');

        if (isset($params['providerId']) && !in_array($params['providerId'], [null, ''])) {
            $providerId = $params['providerId'];
            $query->where('product.provider_id', $providerId);
        }
        if (isset($params['providerCode']) && !in_array($params['providerCode'], [null, ''])) {
            $providerCode = $params['providerCode'];
            $query->where('provider.code', $providerCode);
        }
        
        return $query->paginate($size);
    }

}