<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Plan;
use App\Models\PlanByHospital;
use App\Models\PlanPrice;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class PlanPriceController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('planPrice.index', []);
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {

        $data = Request::all();

        $query = PlanPrice::query();
        $query->with(['plan']);
        $query->with(['hospital']);
        $query->where('status' , 'ACTIVE');
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->WhereHas('plan', function ($querySub) use ($search) {
                $querySub->where('plan.name', 'like', "$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }

        $data = $query->get()->toArray();

        return Response::json(
            [
                'draw' => $data['draw'] ?? null,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]
        );
    }

    /**
     * @param null $id
     * @return JsonResponse
     */
    public function getForm($id = null)
    {
        $method = 'POST';
        $planPrice = isset($id) ? PlanPrice::query()->find($id) : new PlanPrice();
        $product = Product::where('name', 'ONE')->first();
        $plans = Plan::query()
            ->where('product_id', $product->id)
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();
        $view = View::make('planPrice.loads._form', [
            'method' => $method,
            'planPrice' => $planPrice,
            'plans' => $plans,

        ])->render();

        return Response::json(array(
            'html' => $view,
        ));

    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    public function postSave()
    {
        try {

            $data = Request::all();
            if ($data['planPrice_id'] == '') { //Create
                $planPrice = new PlanPrice();
            } else { //Update
                $planPrice = PlanPrice::query()->find($data['planPrice_id']);
            }
            $planPrice->plan_id = $data['plan_id'];
            $planPrice->gender = $data['gender'];
            $planPrice->range_age_from = $data['range_age_from'];
            $planPrice->range_age_to = $data['range_age_to'];
            $baseValue = $data['base_value'];
            $administrativePrice = Config::where('name', config('constants.planPrice.administrativePrice'))->first();
            $farmTax = Config::where('name', config('constants.planPrice.farmInsurance'))->first();
            $discount = Config::where('name', config('constants.planPrice.discountPercentage'))->first();
            $subtotal = round($baseValue + $administrativePrice->value, 2);
            $farmInsurance = round((($subtotal * $farmTax->value) / 100) , 2);
            $monthlyPrice = round(($subtotal + $farmInsurance) , 2);
            $annualPrice = round(($monthlyPrice * 12), 2);
            if ($data['enable_discounted'] === 'YES') {
                $discountPercentage = ($annualPrice * $discount->value) / 100;
                $annualPriceDiscount = $annualPrice - $discountPercentage;
                $planPrice->discount_percentage = $discountPercentage;
                $planPrice->annual_price_discount = $annualPriceDiscount;

            } else {
                $planPrice->annual_price_discount = $annualPrice;
            }
            $planPrice->administrative_price = $administrativePrice->value;
            $planPrice->base_value = $baseValue;
            $planPrice->subtotal = $subtotal;
            $planPrice->farm_insurance = $farmInsurance;
            $planPrice->monthly_price = $monthlyPrice;
            $planPrice->annual_price = $annualPrice;
            $planPrice->label_discount = $data['label_discount'];
            $planPrice->enable_discounted = $data['enable_discounted'];
            $planPrice->status = $data['status'];
            $planPrice->save();

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el precio del plan',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPlansByHospital(Request $request)
    {
        $data = $request::all();
        $hospitalId = $data['hospital_id'];
        $hospital = PlanByHospital::where('hospital_id', $hospitalId)->first();
        $plans = $hospital->plan()->get();

        return $plans;
    }

}
