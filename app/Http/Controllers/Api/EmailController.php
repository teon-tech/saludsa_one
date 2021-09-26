<?php

namespace App\Http\Controllers\Api;

use App\Processes\EmailProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmailController extends ApiBaseController
{

    /**
     * @var EmailProcess
     */
    private $emailProcess;

    /**
     * EmailController constructor.
     * @param EmailProcess $emailProcess
     */
    public function __construct(EmailProcess $emailProcess)
    {
        $this->emailProcess = $emailProcess;
    }

    public function sendTermsAndConditions(Request $request)
    {
        return $this->emailProcess->sendEmailTermsAndConditions($request);
    }
    public function sendSummaryPurchase(Request $request)
    {
        return $this->emailProcess->sendEmailSummaryPurchase($request);
    }

}
