<?php

namespace App\Http\Controllers;

use App\Models\TypeCoverage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class TypeCoverageController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('typeCoverage.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = TypeCoverage::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('type_coverage.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $typeCoverages = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $typeCoverages
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $typeCoverage = isset($id) ? TypeCoverage::find($id) : new TypeCoverage();
        $view = View::make('typeCoverage.loads._form', [
            'method' => $method,
            'typeCoverage' => $typeCoverage
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['typeCoverage_id'] == '') { //Create
                $typeCoverage = new TypeCoverage();
            } else { //Update
                $typeCoverage = TypeCoverage::find($data['typeCoverage_id']);
            }
            $typeCoverage->name = trim($data['name']);
            $typeCoverage->status = trim($data['status']);
            $typeCoverage->weight = $data['weight'];
            $typeCoverage->save();

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
        $validation = Validator::make(Request::all(), ['name' => 'unique:type_coverage,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
