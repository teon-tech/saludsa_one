<?php

namespace App\Processes;

use App\Http\Resources\SubscriptionResource;
use App\Processes\SaleProcess;
use App\Repositories\AuditPaymentRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\KushkiService;
use App\Transformers\KushkiTransformer;
use App\Validators\SubscriptionValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class SubscriptionProcess
 * @package App\Processes
 */
class SubscriptionProcess
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @var SubscriptionValidator
     */
    private $subscriptionValidator;

    /**
     * @var KushkiService
     */
    private $kushkiService;
    /**
     * @var SaleProcess
     */
    private $saleProcess;
    /**
     * @var SaleRepository
     */
    private $saleRepository;

    /**
     * @var KushkiTransformer
     */
    private $kushkiTransformer;

    /**
     * @var AuditPaymentRepository
     */
    private $auditPaymentRepository;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        SubscriptionValidator $subscriptionValidator,
        KushkiService $kushkiService,
        SaleRepository $saleRepository,
        SaleProcess $saleProcess,
        KushkiTransformer $kushkiTranformer,
        AuditPaymentRepository $auditPaymentRepository
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionValidator = $subscriptionValidator;
        $this->kushkiService = $kushkiService;
        $this->saleRepository = $saleRepository;
        $this->saleProcess = $saleProcess;
        $this->kushkiTransformer = $kushkiTranformer;
        $this->auditPaymentRepository = $auditPaymentRepository;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $payload = $request->all();
        $this->subscriptionValidator->create($payload);
        $sale = $this->saleRepository->view($payload['saleId']);

        $tokenKushki = $payload['token'];
        $payloadSubscriptionKushki = $this->kushkiTransformer->buildSubscription($tokenKushki, $sale);

        $response = $this->kushkiService->subscriptions($payloadSubscriptionKushki);
        $responseKushki = $response['data'];
        $statusKushki = $response['status'];
        $this->auditPaymentRepository->save($sale->customer_id, $sale->id, $response['url'], $payloadSubscriptionKushki, $statusKushki, $responseKushki);

        $this->subscriptionRepository->create($response, $payloadSubscriptionKushki, $payload);

        /* if ($periodicity === 'yearly') {
        $this->temporaryDiscount($data['data']['subscriptionId']);
        } */
        return $response;
    }

    public function firstPaymentsubscription(Request $request)
    {

        $payload = $request->all();
        $sale = $this->saleRepository->view($payload['saleId']);
        $customer = $sale->customer;
        $subscription = $sale->subscription[0];
        $firstPaymentPayload = $this->kushkiTransformer->buildFirstPayment($payload['token'], $sale);
        $subscriptionId = $subscription->subscription_id;

        $response = $this->kushkiService->firstSubscriptionPayment($subscriptionId, $firstPaymentPayload);
        $responseKushki = $response['data'];
        $statusKushki = $response['status'];
        $this->auditPaymentRepository->save($sale->customer_id, $sale->id, $response['url'], $firstPaymentPayload, $statusKushki, $responseKushki);

        $statusPayment = $responseKushki['details']['transactionStatus'] ?? null;
        if ($statusKushki == 201 && $statusPayment == 'APPROVAL') {
            // TODO update subscription status, sales and save log subscription_logs
            $this->updateSubscription($subscriptionId, $sale);
        }
        return $response;
    }

    public function updateSubscription($subscriptionId, $sale)
    {
        $sale = $this->saleRepository->view($sale->id);
        $payloadUpdateSubscription = $this->kushkiTransformer->buildUpdatePeriodicity($sale);
        $response = $this->kushkiService->updateSubscription($subscriptionId, $payloadUpdateSubscription);
        $responseKushki = $response['data'];
        $statusKushki = $response['status'];
        $this->auditPaymentRepository->save($sale->customer_id, $sale->id, $response['url'], $payloadUpdateSubscription, $statusKushki, $responseKushki);

        $this->subscriptionRepository->update($payloadUpdateSubscription, $subscriptionId);

    }

    public function handleWebhook(Request $request)
    {
        /* $headers = $request->headers->all();
        $webhookSignature = config('services.kushki.webhookSignature');
        $xKushkiId = $headers['HTTP_X_KUSHKI_ID'];
        $xKushkiSimpleSignature = $headers['HTTP_X_KUSHKI_SIMPLESIGNATURE'];
        $signatureGenerated = hash_hmac("sha256", $xKushkiId, $webhookSignature);
        if ($signatureGenerated === $xKushkiSimpleSignature) {
        $payload = $request->all();
        $this->subscriptionRepository->webhookSave($payload);
        } */
        $payload = $request->all();
        if (empty($payload) || !isset($payload['event'])) {
            // Enter if notification is test and subscription was created
            return [
                'messages' => "Notification received",
                'status' => 200,
            ];
        }
        try {
            $this->subscriptionRepository->webhookSave($payload);
            return [
                'messages' => "Notification received",
                'status' => 200,
            ];
        } catch (\Throwable $th) {
            return [
                'messages' => "Notification received",
                'status' => 200,
            ];
        }

    }

    public function temporaryDiscount($subscriptionId)
    {
        $plan = $this->subscriptionRepository->findPlanBySubscription($subscriptionId);
        $input = [
            "amount" => [
                "subtotalIva" => 0,
                "subtotalIva0" => round($plan->annual_price, 2),
                "ice" => 0,
                "iva" => 0,
                "currency" => "USD",
            ],
            "periods" => 1,

        ];
        $data = $this->kushkiService->temporaryDiscount($subscriptionId, $input);
        $this->subscriptionRepository->saveLogTemporaryDiscount($data, $input, $subscriptionId);
        return $data;
    }
}
