<?php

namespace App\Repositories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class HospitalRepository
{
    /**
     * @return Builder[]|Collection
     */
    public function findAll($params = [])
    {
        $query = Hospital::query()
            ->where('status', 'ACTIVE');

        if (isset($params['query']) && !in_array($params['query'], [null, ''])) {
            $searchValue = $params['query'];
            $query->where(function ($subQuery) use ($searchValue) {
                $subQuery->orWhere('hospital.name', 'like', "%$searchValue%");
                $subQuery->orWhere('hospital.keywords', 'like', "%$searchValue%");
                $subQuery->orWhere('hospital.description', 'like', "%$searchValue%");
                $subQuery->orWhereHas('region', function ($querySub) use ($searchValue) {
                    $querySub->where('region.name', 'like', "%$searchValue%");
                });
            });
        }
        if (isset($params['product']) && !in_array($params['product'], [null, ''])) {
            $product = $params['product'];
            $query->where(function ($subQuery) use ($product) {
                $subQuery->WhereHas('plan', function ($querySub) use ($product) {
                    $querySub->whereHas('product', function($queryProduct) use ($product){
                        $queryProduct->where('product.name' , $product);
                    });
                });
            });
        }
        return $query->get();
    }

}
