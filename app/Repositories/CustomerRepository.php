<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CustomerRepository
 * @package App\Repositories
 */
class CustomerRepository
{
    public function store($data)
    {
        $model = new Customer();
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function storeWithLog($data)
    {
        $model = new Customer();
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function update(Customer $model, $data)
    {
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public static function findById($id)
    {
        try {
            return Customer::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("No existe el cliente con ese identificador");
        }
    }

    /**
     * @param $uid
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function findByUid($uid)
    {
        try {
            return Customer::query()->where('firebase_uid', '=', $uid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("No existe el cliente con ese identificador");
        }
    }

    /**
     * @param Customer $customer
     * @param $product_id
     * @return mixed
     */
    public function addFavorite(Customer $customer, $product_id)
    {
        try {
            $customer->favorites_products()->syncWithoutDetaching([$product_id]);
            $customer->refresh();
            return $customer->favorites_products;
        } catch (\Exception $e) {
            throw  new HttpException(500, 'Ocurrió un error al agregar el producto a favoritos.');
        }
    }

    /**
     * @param Customer $customer
     * @param $product_id
     * @return mixed
     */
    public function removeFavorite(Customer $customer, $product_id)
    {
        try {
            $customer->favorites_products()->detach([$product_id]);
            $customer->refresh();
            return $customer->favorites_products;
        } catch (\Exception $e) {
            throw  new HttpException(500, 'Ocurrió un error al remover el producto de favoritos.');
        }
    }

    public function findByAtt($value, $attr = 'id')
    {
        return Customer::query()->where($attr, $value)->first();
    }
}