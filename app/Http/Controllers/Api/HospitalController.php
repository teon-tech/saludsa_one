<?php

namespace App\Http\Controllers\Api;

use App\Processes\HospitalProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HospitalController extends ApiBaseController
{

    /**
     * @var HospitalProcess
     */
    private $hospitalProcess;

    /**
     * PlanController constructor.
     * @param HospitalProcess $hospitalProcess
     */
    public function __construct(HospitalProcess $hospitalProcess)
    {
        $this->hospitalProcess = $hospitalProcess;
    }

    
    /**
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        return $this->hospitalProcess->findHospitals($request);
    }

}
