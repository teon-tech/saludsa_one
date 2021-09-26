<?php

namespace App\Http\Controllers\Geographic;

use App\Http\Controllers\MyBaseController;
use App\Models\Region;
use App\Models\Province;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ProvinceController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('province.index', [

        ]);
    }

    public function getListProvinces()
    {
        $data = Request::all();
        $query = Province::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('province.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('province.' . $column_name, $dir);
            }
        }
        $provinces = $query
            ->select([
                'province.id as id',
                'province.name as provinceName',
                'region.name as regionName',
                'province.status  as status',
                'province.external_code as externalCode'
            ])
            ->join('region', 'region.id', '=', 'province.region_id')
            ->get()->transform(function ($item) {
            return ['id' => $item->id,
                'name' => $item->regionName . '->' .
                $item->provinceName,
                'status' => $item->status,
                'externalCode' => $item->externalCode
            ];
        })
        ->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $provinces,
            )
        );
    }

    public function getFormProvince($id = null)
    {
        $method = 'POST';
        $province = isset($id) ? Province::find($id) : new Province();
        $regions = Region::pluck('name', 'id')->toArray();
        $view = View::make('province.loads._form', [
            'method' => $method,
            'province' => $province,
            'regions' => $regions,
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }

    public function getListSelect2()
    {
        $data = Request::all();
        $query = Province::query()->select('id', 'name as text');
        if (isset($data['term']) && !empty($data['term'])) {
            $query->where('name', 'like', '%' . $data['term'] . '%');
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $query->where('id', '=', $data['id']);
        }
        if (isset($data['region_id']) && !empty($data['region_id'])) {
            $query->where('region_id', '=', $data['region_id']);
        }
        $query->where('status', '=', 'ACTIVE');
        $query->limit(10)->orderBy('name', 'asc');
        $provincesList = $query->get()->toArray();
        return Response::json(
            $provincesList
        );
    }

    public function postSave()
    {
        try {
            $data = Request::all();
            if ($data['province_id'] == '') { //Create
                $province = new Province();
                $province->status = 'ACTIVE';
            } else { //Update
                $province = Province::find($data['province_id']);
                if (isset($data['status'])) {
                    $province->status = $data['status'];
                }

            }
            $province->name = trim($data['name']);
            $province->region_id = $data['region_id'];
            $province->external_code = $data['external_code'];
            $province->save();
            return Response::json(true);
        } catch (Exception $e) {
            return Response::json(false);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:province,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
