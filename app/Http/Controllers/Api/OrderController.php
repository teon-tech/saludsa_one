<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\OrderResource;
use App\Processes\OrderProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api
 */
class OrderController extends ApiBaseController
{

    /**
     * @var OrderProcess
     */
    private $orderProcess;

    /**
     * OrderController constructor.
     * @param OrderProcess $orderProcess
     */
    public function __construct(OrderProcess $orderProcess)
    {
        $this->orderProcess = $orderProcess;
    }

    /**
     * @param Request $request
     * @return OrderResource
     * @throws \Exception
     */
    public function create(Request $request)
    {
        return $this->orderProcess->create($request);
    }

    /**
     * @param $orderId
     * @return OrderResource
     */
    public function view($orderId)
    {
        return $this->orderProcess->view($orderId);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        return $this->orderProcess->findAll($request);
    }

    /**
     * @param Request $request
     * @return OrderResource
     */
    public function qualification(Request $request)
    {
        return $this->orderProcess->qualification($request);
    }

}
