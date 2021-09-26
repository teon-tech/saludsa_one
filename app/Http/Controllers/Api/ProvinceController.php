<?php

namespace App\Http\Controllers\Api;

use App\Processes\ProvinceProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProvinceController extends ApiBaseController
{

    /**
     * @var ProvinceProcess
     */
    private $provinceProcess;

    /**
     * ProvinceController constructor.
     * @param ProvinceProcess $provinceProcess
     */
    public function __construct(ProvinceProcess $provinceProcess)
    {
        $this->provinceProcess = $provinceProcess;
    }

    
    /**
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        return $this->provinceProcess->findAll($request);
    }

}
