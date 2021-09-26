<?php

namespace App\Http\Controllers\Geographic;

use App\Http\Controllers\MyBaseController;
use App\Models\Country;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CountryController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('country.index', [

        ]);
    }

    public function getListCountries()
    {
        $data = Request::all();
        $query = Country::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('country.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('country.' . $column_name, $dir);
            }
        }
        $products = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $products
            )
        );
    }

    public function getFormCountry($id = null)
    {
        $method = 'POST';
        $country = isset($id) ? Country::find($id) : new Country();
        $view = View::make('country.loads._form', [
            'method' => $method,
            'country' => $country
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }

    public function getListSelect2()
    {
        $data = Request::all();
        $query = Country::query()->select('id', 'name as text');
        if (isset($data['term']) && !empty($data['term'])) {
            $query->where('name', 'like', '%' . $data['term'] . '%');
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $query->where('id', '=', $data['id']);
        }
        $query->where('status', '=', 'ACTIVE');
        $query->limit(10)->orderBy('name', 'asc');
        $countriesList = $query->get()->toArray();
        return Response::json(
            $countriesList
        );
    }

    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['country_id'] == '') { //Create
                $country = new Country();
                $country->status = 'ACTIVE';
            } else { //Update
                $country = Country::find($data['country_id']);
                if (isset($data['status']))
                    $country->status = $data['status'];
            }

            $country->name = trim($data['name']);
            $country->save();
            return Response::json(true);
        } catch (Exception $e) {
            return Response::json(false);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:country,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
