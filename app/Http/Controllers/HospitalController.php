<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Plan;
use App\Models\Product;
use App\Models\PlanByHospital;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class HospitalController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('hospital.index', []);
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {

        $data = Request::all();

        $query = Hospital::query();
        $query->with(['region']);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('hospital.name', 'like', "%$search%");
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
        $hospital = isset($id) ? Hospital::query()->find($id) : new Hospital();
        $words = $hospital->keywords ? explode(',', $hospital->keywords) : [];
        $options = [];
        foreach ($words as $word) {
            $options[$word] = $word;
        }
        $product = Product::where('name' , 'ONE')->first();
        $plans = Plan::where('product_id', $product->id)
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();

        $regions = Region::query()
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();
        $planSelected = $hospital->id ? PlanByHospital::query()
            ->where('hospital_id', $hospital->id)
            ->get()
            ->pluck('plan_id')
            ->toArray() : [];

        $view = View::make('hospital.loads._form', [
            'method' => $method,
            'hospital' => $hospital,
            'words' => $words,
            'options' => $options,
            'plans' => $plans,
            'regions' => $regions,
            'planSelected' => $planSelected

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
            if ($data['hospital_id'] == '') { //Create
                $hospital = new Hospital();
            } else { //Update
                $hospital = Hospital::query()->find($data['hospital_id']);
            }
            $hospital->name = $data['name'];
            $hospital->status = $data['status'];
            $hospital->region_id = $data['region_id'];
            $hospital->keywords = isset($data['keywords']) ? implode(',', $data['keywords']) : null;
            $hospital->description = $data['description'];
            $hospital->save();

            $planByHospital = PlanByHospital::query()
                ->where('hospital_id', $hospital->id)
                ->first() ?? new PlanByHospital();
            $planByHospital->hospital_id = $hospital->id;
            $planByHospital->plan_id = $data['plan_id'];
            $planByHospital->save();

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el hospital',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:hospital,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}
