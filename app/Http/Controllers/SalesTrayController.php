<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleServiceLog;
use App\Processes\SaleProcess;
use App\Repositories\SaleServiceLogRepository;
use App\Services\SaleService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SalesTrayController extends MyBaseController
{
    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * @var SaleServiceLogRepository
     */
    private $saleServiceLogRepository;
    /**
     * @var SaleProcess
     */
    private $saleProcess;

    public function __construct(
        SaleService $saleService,
        SaleServiceLogRepository $saleServiceLogRepository,
        SaleProcess $saleProcess) {
        $this->saleService = $saleService;
        $this->saleServiceLogRepository = $saleServiceLogRepository;
        $this->saleProcess = $saleProcess;
    }
    public function index()
    {
        $this->layout->content = View::make('salesTray.index', []);
    }

    public function getList()
    {

        $data = Request::all();
        $query = Sale::query();
        $query->with(['customerData']);
        $query->with([
            'details' => function ($subquery) {
                $subquery->with(['planPrice' => function ($subQuery) {
                    $subQuery->with(['plan']);
                },
                ]);},
        ]);
        $query->with(['subscription']);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->WhereHas('customerData', function ($querySub) use ($search) {
                $querySub->where('customer.name', 'like', "$search%");
                $querySub->orwhere('customer.father_last_name', 'like', "$search%");
                $querySub->orwhere('customer.email', 'like', "$search%");
                $querySub->orwhere('customer.document', 'like', "$search%");
            });

            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }
        $query->orderBy('sales.id' , 'DESC');
        $data = $query->get()->toArray();

        return Response::json(
            [
                'draw' => $data['draw'] ?? null,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $model = Sale::query()->find($id);
        $view = View::make('salesTray.loads._form', [
            'method' => $method,
            'model' => $model,
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }

    public function getFormService($id = null)
    {
        $method = 'POST';
        $servicesSale = SaleServiceLog::query()
            ->where('sale_id', $id)
            ->whereIn('name', ['paymentHeaderEntry', 'createPaymentDetail', 'createPaymentMethod'])
            ->get();

        $servicesSigmep = SaleServiceLog::query()
            ->where('sale_id', $id)
            ->where('name', 'ServiceSigmep')
            ->get();

        $model = Sale::query()->find($id);
        $view = View::make('salesTray.loads._form_service', [
            'method' => $method,
            'model' => $model,
            'servicesSale' => $servicesSale,
            'servicesSigmep' => $servicesSigmep,
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }

    public function updateServiceSale()
    {
        $data = Request::all();
        $id = $data['id'];
        $service = SaleServiceLog::find($id);
        $sale = Sale::find($service->sale_id);

        if ($service->name === 'paymentHeaderEntry') {
            $this->saleProcess->salesServiceRetryMethod($sale, $id);
        }
        if ($service->name === 'createPaymentDetail') {
            $this->saleProcess->createPaymentDetail($sale, $id);
        }
        if ($service->name === 'createPaymentMethod') {
            $this->saleProcess->createPaymentDetail($sale, $id);
        }
        if ($service->name === 'ServiceSigmep') {
            $this->saleProcess->serviceSigmep($sale, $id);
        }

        return Response::json([
            'status' => 'success',
        ], 200);
    }

    public function getFormInfoService($id = null)
    {
        $method = 'POST';
        $service = SaleServiceLog::find($id);
        $view = View::make('salesTray.loads._form_infoService', [
            'method' => $method,
            'service' => $service
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }
}
