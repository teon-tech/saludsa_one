<?php

namespace App\Http\Controllers\Api;

use App\Processes\RegisterCivilProcess;
use Illuminate\Http\Request;

class RegisterCivilController extends ApiBaseController
{

    /**
     * @var RegisterCivilProcess
     */
    private $registerCivilProcess;

    public function __construct(RegisterCivilProcess $registerCivilProcess)
    {
        $this->registerCivilProcess = $registerCivilProcess;
    }

    public function findUserByCI(Request $request)
    {
        return $this->registerCivilProcess->findUserByCI($request);
    }
}
