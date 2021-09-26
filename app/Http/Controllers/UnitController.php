<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UnitController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('unit.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Unit::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('unit.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $units = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $units
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $unit = isset($id) ? Unit::find($id) : new Unit();
        $view = View::make('unit.loads._form', [
            'method' => $method,
            'unit' => $unit
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['unit_id'] == '') { //Create
                $unit = new Unit();
            } else { //Update
                $unit = Unit::find($data['unit_id']);
            }
            $unit->name = trim($data['name']);
            $unit->values = trim($data['values']);
            $unit->save();

            return Response::json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error'
            ]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:unit,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
