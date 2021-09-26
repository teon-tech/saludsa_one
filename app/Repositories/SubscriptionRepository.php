<?php

namespace App\Repositories;

use App\Models\AuditPayment;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Subscription;
use App\Models\SubscriptionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;

class SubscriptionRepository
{
    /**
     * @param $responseKushki
     * @param $payloadKushki
     * @param $input
     * @throws \Exception
     */
    public function create($responseKushki, $payloadKushki, $input)
    {
        try {
            DB::beginTransaction();
            $agent = new Agent();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $version = $agent->version($platform);
            $infoUser = [
                'device' => $agent->device(),
                'operatingSystem' => $platform,
                'versionSO' => $agent->version($platform),
                'browser' => $browser,
                'versionBrowser' => $agent->version($browser),
                'ip' => Request::ip()
            ];
            $saleId = $input['saleId'];
            $sale = Sale::query()->find($saleId);
            $detail = $sale->details[0];

            $amount = $payloadKushki['amount'];
            if ($detail->subscription_type === 'ANNUAL') {
                $total = (float)$detail->annual_with_discount;
            } else {
                $total = (float)$detail->monthly_price;
            }

            $subscriptionExist = Subscription::query()->where('sale_id', $saleId)->first();
            $subscription = $subscriptionExist ?? new Subscription();
            $subscription->sale_id = $saleId;
            $subscription->token = $payloadKushki['token'];
            $subscription->status_subscription = Subscription::STATUS_PENDING;
            $subscription->subscription_id = $responseKushki['data']['subscriptionId'] ?? null;
            $subscription->type = $detail->subscription_type;
            $subscription->start_date = $payloadKushki['startDate'] ?? null;
            $subscription->next_payment_at = $payloadKushki['startDate'] ?? null;
            $subscription->total = $total;
            $subscription->total_info = $amount;
            $subscription->status = Subscription::STATUS_INACTIVE;
            $subscription->info_user = $infoUser;
            $subscription->save();

            $sale = Sale::query()->find($saleId);
            $sale->payment_method_id = 1;
            $sale->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function webhookSave($payload)
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $version = $agent->version($platform);
        $infoUser = [
            'device' => $agent->device(),
            'operatingSystem' => $platform,
            'versionSO' => $agent->version($platform),
            'browser' => $browser,
            'versionBrowser' => $agent->version($browser),
            'ip' => Request::ip()
        ];
        $event = $payload['event'];
        $eventName = $payload['name'];
        $subscriptionId = $event['subscriptionId'];
        $subscription = Subscription::where('subscription_id', $subscriptionId)->first();
        $sale = Sale::find($subscription->sale_id);
        if ($eventName === config('services.kushki.succesfullCharge')) {
            $subscription->status = Subscription::STATUS_ACTIVE;
            $subscription->status_subscription = Subscription::STATUS_APPROVED;
            $subscription->reason_status = 'SUCCESSFULLY_CHARGE';
            $lastPaymentAt = new Carbon(gmdate("Y-m-d\ H:i:s", $event['lastChargeDate']));
            $sale->status_payment = Sale::STATUS_PAYMENT_APPROVED;
        }

        if ($eventName === config('services.kushki.declinedCharge')) {
            $subscription->status = Subscription::STATUS_INACTIVE;
            $subscription->status_subscription = 'REJECT_ATEMPT';
            $subscription->reason_status = 'DECLINED_CHARGE';
            $sale->status_payment = 'REJECT_ATEMPT';
        }

        if ($eventName === config('services.kushki.failedRetry')) {
            $subscription->status = Subscription::STATUS_INACTIVE;
            $subscription->status_subscription = 'REJECT_ATEMPT';
            $subscription->reason_status = 'FAILED_RETRY';
            $sale->status_payment = 'REJECT_ATEMPT';
        }

        if ($eventName === config('services.kushki.subscriptionDelete')) {
            $subscription->status = Subscription::STATUS_INACTIVE;
            $subscription->status_subscription = Subscription::STATUS_REJECTED;
            $subscription->reason_status = 'SUBSCRIPTION_DELETE';
            $lastPaymentAt = new Carbon(gmdate("Y-m-d\ H:i:s", $event['lastChargeDate']));
            $sale->status_payment = 'REJECT_ATEMPT';
        }
        $subscription->bank_info = $event['binInfo'];
        $subscription->number_card = $event['maskedCardNumber'];

        $subscription->last_payment_at = $lastPaymentAt ?? null;
        if ($event['periodicity'] === 'monthly') {
            $subscription->next_payment_at = isset($lastPaymentAt) ? $lastPaymentAt->addMonth(1) : null;
        } else {
            $subscription->next_payment_at = isset($lastPaymentAt) ? $lastPaymentAt->addYear(1) : null;
        }
        $subscription->start_date = gmdate("Y-m-d\ H:i:s", $event['startDate']);
        $amount = $event['amount'];
        $total = $amount['subtotalIva'] + $amount['subtotalIva0'] + $amount['ice'] + $amount['iva'];
        $subscription->total_info = $amount;
        $subscription->info_user = $subscription->info_user ?? [] + $infoUser;
        $subscription->save();

        $subscriptionLog = new SubscriptionLog();
        $subscriptionLog->subscription_id = $subscription->id;
        $subscriptionLog->transaction_status = $payload['name'];
        $subscriptionLog->total = $total;
        $subscriptionLog->payment_date = Carbon::now();
        $subscriptionLog->ticket_number = $event['ticketNumber'] ?? null;
        $subscriptionLog->approval_code = $event['approvalCode'] ?? null;
        $subscriptionLog->transaction_code = $event['transactionReference'] ?? null;
        $subscriptionLog->total_info = $amount;
        $subscriptionLog->info_user = $infoUser;
        $subscriptionLog->save();
        $sale->save();

        $response = [
            'sale' => $sale,
        ];
        return $response;
    }

    public function findPlanBySubscription($subscriptionId)
    {

        $subscription = Subscription::where('subscription_id', $subscriptionId)->first();
        $saleDetail = SaleDetail::where('sale_id', $subscription->sale_id)->first();
        return $saleDetail;

    }

    public function saveLogTemporaryDiscount($data, $payload, $subscriptionId)
    {
        $subscription = Subscription::where('subscription_id', $subscriptionId)->first();
        $sale = Sale::find($subscription->sale_id);
        $auditPayment = new AuditPayment();
        $auditPayment->customer_id = $sale->customer_id;
        $auditPayment->sale_id = $sale->id;
        $auditPayment->url = config('services.kushki.url');
        $auditPayment->payload = $payload;
        $auditPayment->response = $data;
        $auditPayment->status_code = $data['status'];
        $auditPayment->save();
    }

    public function getSubscriptions()
    {
        return Subscription::query()
            ->where('status_subscription', 'PENDING')
            ->orWhere('status_subscription', 'ATTEMPT_REJECTED')->get();
    }

    public function update($payload, $subscriptionId)
    {
        $amount = $payload['amount'];
        $subscription = Subscription::query()->where('subscription_id', $subscriptionId)->first();
        $subscription->start_date = $payload['startDate'];
        $subscription->next_payment_at = $payload['startDate'];
        $subscription->total_info = $amount;
        $subscription->save();

        $subscriptionLog = new SubscriptionLog();
        $subscriptionLog->subscription_id = $subscription->id;
        $subscriptionLog->transaction_status = 'UPDATED_SUBSCRIPTION';
        $subscriptionLog->payment_date = Carbon::now();
        $subscriptionLog->total = (float)$amount['subtotalIva0'];
        $subscriptionLog->total_info = $amount;
        $subscriptionLog->save();
    }
}
