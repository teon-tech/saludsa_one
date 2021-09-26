<?php

namespace App\Http\Controllers\Api\Geographic;

use App\Http\Controllers\Api\ApiBaseController;
use App\Processes\LocatorProcess;
use Illuminate\Http\Request;

class LocatorController extends ApiBaseController
{

    private $locatorProcess;

    public function __construct(LocatorProcess $locatorProcess)
    {
        $this->locatorProcess = $locatorProcess;
    }

    public function coverage(Request $request)
    {
        return $this->locatorProcess->coverage($request);
    }

}