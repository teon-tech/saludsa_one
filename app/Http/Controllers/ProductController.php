<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ProductController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('product.index', []);
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {
        $data = Request::all();

        $query = Product::query();
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('product.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }
        $query->orderBy('product.created_at', 'desc');
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('product.' . $column_name, $dir);
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
        $model = isset($id) ? Product::query()->find($id) : new Product();

        $view = View::make('product.loads._form', [
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
            $keyIdModel = 'model_id';
            if ($data[$keyIdModel] == '') { //Create
                $product = new Product();
                $product->status = Config::get('constants.active_status');
            } else { //Update
                $product = Product::query()->find($data[$keyIdModel]);
                $product->status = $data['status'];
            }
            $product->name = $data['name'];
            $product->save();
            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el producto',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:product,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}
