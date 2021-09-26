<?php

namespace App\Http\Controllers\Geographic;

use App\Http\Controllers\MyBaseController;
use App\Models\Region;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class RegionController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('region.index', [

        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $query = Region::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('region.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }
        $regions = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $regions
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $region = isset($id) ? Region::find($id) : new Region();
        $view = View::make('region.loads._form', [
            'method' => $method,
            'region' => $region
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }

    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['region_id'] == '') { //Create
                $region = new Region();
                $region->status = 'ACTIVE';
            } else { //Update
                $region = Region::find($data['region_id']);
                if (isset($data['status']))
                    $region->status = $data['status'];
            }

            $region->name = trim($data['name']);
            $region->external_code = $data['external_code'];
            $region->save();
            return Response::json(true);
        } catch (Exception $e) {
            return Response::json(false);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:region,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
