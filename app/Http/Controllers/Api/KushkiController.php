<?php

namespace App\Http\Controllers\Api;

use App\Processes\KushkiProcess;
use Illuminate\Http\Request;

class KushkiController extends ApiBaseController
{

    /**
     * @var KushkiProcess
     */
    private $kushkiProcess;

    public function __construct(KushkiProcess $kushkiProcess)
    {
        $this->kushkiProcess = $kushkiProcess;
    }

    public function subscriptions(Request $request)
    {
        return $this->kushkiProcess->subscriptions($request);
    }
}
