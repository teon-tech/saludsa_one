<?php


namespace App\Http\Controllers\Api;


use App\Http\Resources\ProviderResource;
use App\Processes\ProviderProcess;
use Illuminate\Http\Request;

class ProviderController extends ApiBaseController
{

    /**
     * @var ProviderProcess
     */
    private $providerProcess;

    /**
     * ProviderController constructor.
     * @param ProviderProcess $providerProcess
     */
    public function __construct(ProviderProcess $providerProcess)
    {
        $this->providerProcess = $providerProcess;
    }

    /**
     * @param $providerId
     * @return ProviderResource
     */
    public function view($providerId)
    {
        return $this->providerProcess->view($providerId);
    }

    /**
     * @param $code
     * @return ProviderResource
     */
    public function viewByCode($code)
    {
        return $this->providerProcess->viewByCode($code);
    }

     /**
     * @param Request $request
     * @return ProviderResource
     */
    public function getAll(Request $request)
    {
        return $this->providerProcess->getAll($request);
    }

}
