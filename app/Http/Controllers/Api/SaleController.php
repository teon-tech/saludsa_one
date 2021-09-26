<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SaleResource;
use App\Processes\SaleProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends ApiBaseController
{

    /**
     * @var SaleProcess
     */
    private $saleProcess;

    /**
     * SaleController constructor.
     * @param SaleProcess $saleProcess
     */
    public function __construct(SaleProcess $saleProcess)
    {
        $this->saleProcess = $saleProcess;
    }


    /**
     * @param Request $request
     * @return SaleResource
     * @throws \Exception
     */
    public function saveSale(Request $request)
    {
        return $this->saleProcess->saveSale($request);
    }

    public function view($saleId)
    {
        return $this->saleProcess->view($saleId);
    }

}
