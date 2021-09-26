<?php

namespace App\Http\Controllers;

use App\Exports\InscriptionExport;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('orders.index');
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {
        $data = Request::all();
        $query = Order::query();
        $query->select(
            [
                "order.id as id",
                "order.status as status",
                "provider.name as providerName",
                "order.created_at",
                "order.payment_status as payment_status",
                "order.status as status",
                "customer.document as customerDocument",
                "customer.email as customerEmail",
                "customer.name as customerName",
                "customer.last_name as customerLastName",
            ]
        );
        $query->join('customer', 'customer.id', '=', 'order.customer_id');
        $query->join('provider', 'provider.id', '=', 'order.provider_id');

        $providerId = Auth::user()->provider_id ?? null;

        if ($providerId) {
            $query->where('order.provider_id', '=', $providerId);
        }

        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('order.id', 'like', "%$search%");
                $subQuery->orWhere('provider.name', 'like', "%$search%");
                $subQuery->orWhere('customer.email', 'like', "%$search%");
                $subQuery->orWhere('customer.document', 'like', "%$search%");
                $subQuery->orWhere('customer.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }

        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }

        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $query->orderBy('order.created_at', 'desc');
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy($column_name, $dir);
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
        $model = Order::query()->find($id);
        $status = Order::translate_status;
        $view = View::make('orders.loads._form', [
            'method' => $method,
            'model' => $model,
            'status' => $status
        ])->render();
        return Response::json(array(
            'html' => $view
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
            $form = Order::query()->find($data[$keyIdModel]);
            $form->status = $data['status'];
            $form->save();
            return Response::json(true);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error',
                'message' => 'Error al cambiar de estado de la inscripción',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @return \Illuminate\Http\Response|BinaryFileResponse
     */
    public function postExport()
    {
        ob_end_clean();
        ob_start();

        $data = Request::all();

        $eventId = $data['eventId'];
        return (new InscriptionExport($eventId))->download('reporte.xlsx');
    }

}
