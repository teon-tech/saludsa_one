<?php


namespace App\Repositories;


use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class FavoriteProductRepository
{

    /**
     * @param $customerId
     * @param $productId
     * @return FavoriteProduct|Builder|Model|object|null
     */
    public function create($customerId, $productId)
    {
        $favoriteProduct = FavoriteProduct::query()
                ->where('product_id', $productId)
                ->where('customer_id', $customerId)
                ->first() ?? new FavoriteProduct();
        $favoriteProduct->product_id = $productId;
        $favoriteProduct->customer_id = $customerId;
        $favoriteProduct->save();
        return $favoriteProduct->product;
    }

    public function delete($customerId, $productId)
    {
        return $favoriteProduct = FavoriteProduct::query()
            ->where('product_id', $productId)
            ->where('customer_id', $customerId)
            ->delete();
    }

    /**
     * @param $uid
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function findAllBy($uid, $params = [])
    {
        $size = isset($params['size']) && $params['size'] ? $params['size'] : 10;

        $query = Product::query();
        $query->select('product.*');
        $query->with(['provider', 'images', 'categories']);
        $query->select('product.*');
        $query->join('provider', 'provider.id', '=', 'product.provider_id');
        $query->join('favorite_products', 'favorite_products.product_id', '=', 'product.id');
        $query->join('customer', 'customer.id', '=', 'favorite_products.customer_id');
        $query->where('product.status', Config::get('constants.active_status'));
        $query->where('provider.status', Config::get('constants.active_status'));

        $query->where('customer.firebase_uid', '=', $uid);
        $query->groupBy('product.id');

        return $query->paginate($size);
    }

}