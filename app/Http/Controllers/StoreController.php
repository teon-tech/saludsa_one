<?php


namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Provider;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class StoreController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('store.index');
    }
    
    /**
     * @return JsonResponse
     */
    public function getList()
    {
        $data = Request::all();

        $query = Store::query();
        $query->with(['provider']);
        $providerId = Auth::user()->provider_id;
        if ($providerId) {
            $query->where('provider_id', '=', $providerId);
        }
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('store.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $stores = $query->get()->toArray();
        return Response::json(
            [
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $stores
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function getForm($id = null)
    {
        $method = 'POST';
        $store = isset($id) && $id ? Store::query()->find($id) : new Store();
        $providers = Provider::query()->get()
            ->pluck('name', 'id')
            ->toArray();

        $cities = City::query()
            ->select([
                'city.id as cityId', 
                'city.name as cityName', 
                'province.name as provinceName', 
                'country.name as countryName'
                    ])
            ->join('province', 'province.id', '=', 'city.province_id')
            ->join('country', 'country.id', '=', 'province.country_id')
            ->get()->transform(function ($item)
            {
            return ['id' => $item->cityId, 
                    'name' => $item->countryName . '->' . 
                              $item->provinceName . '->' . 
                              $item->cityName];
            })
            ->pluck('name', 'id')
            ->toArray();
        $view = View::make('store.loads._form', [
                'method' => $method, 
                'store' => $store, 
                'providers' => $providers, 
                'cities' => $cities
                ])->render();

        return Response::json(['html' => $view]);
    }

    /**
     * @return JsonResponse
     */
    public function postSave()
    {
        try {
            DB::beginTransaction();
            $data = Request::all();

            if ($data['store_id'] == '') { //Create
                $store = new Store();
            } else { //Update
                $store = Store::find($data['store_id']);
            }
            $store->name = trim($data['name']);
            $store->address = trim($data['address']);
            $store->provider_id = trim($data['provider_id']);
            $store->city_id = trim($data['city_id']);
            $store->status = trim($data['status']);
            $store->save();
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'error'
            ]);
        }
    } 
       
    /**
     * @return JsonResponse
     */
    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:store,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}