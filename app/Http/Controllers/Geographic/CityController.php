<?php

namespace App\Http\Controllers\Geographic;

use App\Http\Controllers\MyBaseController;
use App\Models\City;
use App\Models\Province;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CityController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {

        $this->layout->content = View::make('city.index', [
        ]);
    }

    public function getList()
    {

        $data = Request::all();

        $query = City::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('city.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }
        $cities = $query
            ->select([
                'city.id as cityId',
                'city.name as cityName',
                'province.name as provinceName',
                'country.name as countryName',
                'city.status as cityStatus',
            ])
            ->join('province', 'province.id', '=', 'city.province_id')
            ->join('country', 'country.id', '=', 'province.country_id')
            ->get()->transform(function ($item) {
            return ['id' => $item->cityId,
                'name' => $item->countryName . '->' .
                $item->provinceName . '->' .
                $item->cityName,
                'status' => $item->cityStatus];
        })
            ->toArray();

        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $cities,
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $city = isset($id) ? City::find($id) : new City();
        $provinces = Province::query()
            ->select([
                'province.id as provinceId',
                'province.name as provinceName',
                'country.name as countryName',
            ])
            ->join('country', 'country.id', '=', 'province.country_id')
            ->get()->transform(function ($item) {
            return ['id' => $item->provinceId,
                'name' => $item->countryName . '->' .
                $item->provinceName,
            ];
        })
            ->pluck('name', 'id')
            ->toArray();
        $view = View::make('city.loads._form', [
            'method' => $method,
            'city' => $city,
            'provinces' => $provinces,
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }

    public function postSave()
    {
        try {

            $data = Request::all();

            if ($data['city_id'] == '') { //Create
                $city = new City();
            } else { //Update
                $city = City::find($data['city_id']);
            }
            $city->name = trim($data['name']);
            $city->province_id = trim($data['province_id']);
            $city->status = trim($data['status']);
            $city->save();

            return Response::json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error',
            ]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:city,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
