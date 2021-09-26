<?php

namespace App\Http\Controllers\Api;

use App\Processes\PlanProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlanController extends ApiBaseController
{

    /**
     * @var PlanProcess
     */
    private $planProcess;

    /**
     * PlanController constructor.
     * @param PlanProcess $planProcess
     */
    public function __construct(PlanProcess $planProcess)
    {
        $this->planProcess = $planProcess;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function comparativePlan(Request $request)
    {
        return $this->planProcess->comparativePlan($request);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        return $this->planProcess->findPlans($request);
    }

    /**
     * @param $id
     * @return \App\Http\Resources\PlanResource
     */
    public function findById($id)
    {
        return $this->planProcess->findById($id);
    }

    /**
     * @param $code
     * @return \App\Http\Resources\PlanResource
     */
    public function findByCode($code)
    {
        return $this->planProcess->findByCode($code);
    }

     /**
     * @return AnonymousResourceCollection
     */
    public function getPlanWithPrice(Request $request)
    {
        return $this->planProcess->getPlanWithPrice($request);
    }
}
