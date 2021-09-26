<?php

namespace App\Http\Controllers;


use App\Models\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ConfigController extends MyBaseController
{
    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('config.index', []);
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {
        $data = Request::all();

        $query = Config::query();
        $contractNumber = [
            config('constants.generateContractNumber.initialValue'),
            config('constants.generateContractNumber.limitValue')
        ];
        $query->whereIn('name' , $contractNumber);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('config.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }
        $query->orderBy('config.created_at', 'desc');
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('config.' . $column_name, $dir);
            }
        }

        $data = $query->get()->toArray();

        return Response::json(
            [
                'draw' => $data['draw'] ?? null,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
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
        $model = isset($id) ? Config::query()->find($id) : new Config();

        $view = View::make('config.loads._form', [
            'method' => $method,
            'model' => $model,
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
            if ($data['config_id'] == '') { //Create
                $config = new Config();
            } else { //Update
                $config = Config::query()->find($data['config_id']);
            }
            $config->value = $data['value'];
            $config->save();
            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar la configuración',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }


}
