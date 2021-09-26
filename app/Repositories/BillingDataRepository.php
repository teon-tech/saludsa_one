<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * Class BillingDataRepository
 * @package App\Repositories
 */
class BillingDataRepository
{
    public function store(Customer $customer, $data)
    {
        try {
            DB::beginTransaction();
            if ($data['default'] == 'YES') {
                $customer->billing_data()->update(['default' => 'NO']);
            }
            $model = new BillingData();
            $model->fill($data);
            $model->save();
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw  new HttpException(500, $e->getMessage());
        }
    }

    public function update(Customer $customer, BillingData $model, $data)
    {
        try {
            DB::beginTransaction();
            if ($data['default'] == 'YES') {
                $customer->billing_data()->update(['default' => 'NO']);
            }
            $model->fill($data);
            $model->save();
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw  new HttpException(500, $e->getMessage());
        }
    }

    public static function findById($id)
    {
        try {
            return BillingData::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("No existe el dato de facturación con ese identificador.");
        }
    }

    /**
     * @param Customer $customer
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public static function findByIdCustomer(Customer $customer, $id)
    {
        try {
            return $customer->billing_data()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                "No existe el dato de facturación con ese identificador o los datos de facturación no pertenecen al cliente."
            );
        }
    }

    public function findAllByCustomer(Customer $customer)
    {
        try {
            $query_model = $customer->billing_data()->getQuery();
            $query_model = QueryBuilder::for ($query_model)
                ->allowedSorts('id', 'name', 'default')
                ->allowedFilters(['name', 'default']);
            return $query_model->jsonPaginate();
        } catch (\Exception $e) {
            throw  new HttpException(500, $e->getMessage());
        }
    }

    public function delete(Customer $customer, $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->findByIdCustomer($customer, $id);
            $model->delete();
            DB::commit();
            return true;
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            throw new ModelNotFoundException("No existe el dato de facturación con ese identificador.");
        } catch (\Exception $e) {
            DB::rollback();
            throw new HttpException(500, $e->getMessage());
        }
    }
}