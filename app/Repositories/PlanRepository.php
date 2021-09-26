<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Models\Hospital;
use App\Models\PlanPrice;
use App\Models\PlanByHospital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository
{

    public function findBy($value, $attr = 'id')
    {
        return Plan::query()->where($attr, $value)->first();
    }

    /**
     * @return Builder[]|Collection
     */
    public function findAll($params = [])
    {
        $query = Plan::query()->where('plan.status', 'ACTIVE');
        $query->WhereHas('product', function ($querySub) {
            $querySub->where('product.name', 'PLAN');
        });

        if (isset($params['enableComparative']) && !in_array($params['enableComparative'], [null, '', 'NO'])) {
            $query->where('plan.isComparative', '=', $params['enableComparative']);
        }
        if (isset($params['query']) && !in_array($params['query'], [null, ''])) {
            $searchValue = $params['query'];
            $query->where(function ($subQuery) use ($searchValue) {
                $subQuery->orWhere('plan.name', 'like', "%$searchValue%");
                $subQuery->orWhere('plan.code', 'like', "%$searchValue%");
                $subQuery->orWhere('plan.subtitle', 'like', "%$searchValue%");
                $subQuery->orWhere('plan.keywords', 'like', "%$searchValue%");
                $subQuery->orWhereHas('typePlan', function ($querySub) use ($searchValue) {
                    $querySub->where('type_plan.name', 'like', "%$searchValue%");
                });
                $subQuery->orWhereHas('coverages', function ($querySub) use ($searchValue) {
                    $querySub->where('coverage.name', 'like', "%$searchValue%");
                });
            });
        }
        return $query->orderBy('plan.weight', 'asc')->get();
    }

    public function getPlanWithPrice($params = [])
    {
        $query = PlanPrice::query();
        $query->where('plan_price.status', PlanPrice::STATUS_ACTIVE);
        $query->select('plan_price.*');
        $hospital = PlanByHospital::where('hospital_id', $params['hospitalId'])->first();
        $planId = $hospital->plan_id;
        $age = $params['age'];
        $gender = $params['gender'];
        $product = $params['product'];
        $isMaternity = 'NO';
        $query->where(function ($subQuery) use ($gender, $age, $product, $planId) {
            $subQuery->where('plan_price.gender', $gender);
            $subQuery->where('plan_price.range_age_from', '<=', $age);
            $subQuery->where('plan_price.range_age_to', '>=', $age);
            $subQuery->where('plan_price.plan_id', $planId);

            $subQuery->whereHas('plan', function ($querySub) use ($product) {
                $querySub->whereHas('product', function ($queryProduct) use ($product) {
                    $queryProduct->where('product.name', $product);
                });
            });
        });
        if ($gender === 'MALE') {
            $query->with([
                'plan' => function ($subquery) use ($isMaternity) {
                    $subquery->with(['coverages' => function ($queryCoverage) use ($isMaternity) {
                        $queryCoverage->where('coverage.isMaternity', $isMaternity);
                    }]);
                }
            ]);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return Plan::query()->find($id);

    }

}
