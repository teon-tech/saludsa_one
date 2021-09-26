<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\SubscriptionResource;
use App\Processes\SubscriptionProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Api
 */
class SubscriptionController extends ApiBaseController
{

    /**
     * @var SubscriptionProcess
     */
    private $subscriptionProcess;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionProcess $subscriptionProcess
     */
    public function __construct(SubscriptionProcess $subscriptionProcess)
    {
        $this->subscriptionProcess = $subscriptionProcess;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $response = $this->subscriptionProcess->create($request);
        return response($response, $response['status']);
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Notificacion de kushki', $request->all());
        return $this->subscriptionProcess->handleWebhook($request);
    }

    public function firstPaymentsubscription(Request $request)
    {
        $response = $this->subscriptionProcess->firstPaymentsubscription($request);
        return response($response, $response['status']);
    }

}
