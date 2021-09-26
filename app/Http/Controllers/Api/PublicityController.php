<?php

namespace App\Http\Controllers\Api;


use App\Processes\PublicityProcess;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicityController extends ApiBaseController
{

    /**
     * @var PublicityProcess
     */
    private $publicityProcess;

    /**
     * PublicityController constructor.
     * @param PublicityProcess $publicityProcess
     */
    public function __construct(PublicityProcess $publicityProcess)
    {
        $this->publicityProcess = $publicityProcess;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function findAll()
    {
        return $this->publicityProcess->findAll();
    }
}
