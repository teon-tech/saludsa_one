<?php

namespace App\Http\Controllers;

use App\Models\TypePlan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class TypePlanController extends MyBaseController
{

    /**
     *
     */
        public function index()
        {
            $this->layout->content = View::make('typePlan.index', [
            ]);
        }
    

    public function getList()
    {
        $data = Request::all();

        $query = TypePlan::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('type_plan.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $typePlans = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $typePlans
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $typePlan = isset($id) ? TypePlan::find($id) : new TypePlan();
        $view = View::make('typePlan.loads._form', [
            'method' => $method,
            'typePlan' => $typePlan
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        
        try {
            $data = Request::all();

            if ($data['typePlan_id'] == '') { //Create
                $typePlan = new TypePlan();
            } else { //Update
                $typePlan = TypePlan::find($data['typePlan_id']);
            }
            $typePlan->name = trim($data['name']);
            $typePlan->status = trim($data['status']);
            $typePlan->save();

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
        $validation = Validator::make(Request::all(), ['name' => 'unique:type_plan,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    } 
}
