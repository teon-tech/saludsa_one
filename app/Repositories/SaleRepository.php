<?php

namespace App\Repositories;

use App\Components\Util;
use App\Models\PlanPrice;
use App\Models\Sale;
use App\Models\AuditPayment;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\BillingData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class SaleRepository
{

    public function saveSale($input)
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
            $customerExist = Customer::query()
                ->where('email', $input['customer']['email'])
                ->where('document', $input['customer']['document'])
                ->first();
            $customer = $customerExist ?? new Customer();
            $customerData = $input['customer'];
            $customer->name = $customerData['name'] ?? null;
            $customer->email = $customerData['email'];
            $customer->phone = $customerData['phone'];
            $customer->father_last_name = $customerData['father_last_name'] ?? null;
            $customer->mother_last_name = $customerData['mother_last_name'] ?? null;
            $customer->document_type = $customerData['document_type'] ?? null;
            $customer->document = $customerData['document'] ?? null;
            $customer->years_old = $customerData['years_old'];
            $customer->birth_date = $customerData['birthdate'] ?? null;
            $customer->province_id = $customerData['province_id'];
            $customer->direction = $customerData['direction'];
            $customer->civil_status = $customerData['civil_status'] ?? null;
            $customer->gender = $customerData['gender'];
            $customer->save();

            $billingExist = BillingData::query()->where('document', $input['billing_data']['document'])->first();
            $billing = $billingExist ?? new BillingData();
            $billingData = $input['billing_data'];
            $billing->customer_id = $customer->id;
            $billing->name = $billingData['name'];
            $billing->last_name = $billingData['last_name'];
            $billing->document_type = $billingData['document_type'];
            $billing->document = $billingData['document'];
            $billing->direction = $billingData['direction'];
            $billing->save();

            $sale = new Sale();
            $sale->customer_id = $customer->id;
            $sale->customer = $customerData;
            $sale->billing_data_id = $billing->id;
            $sale->billing_data = $billingData;
            $sale->province_id = $customer->province_id;
            $sale->status_payment = Sale::STATUS_PAYMENT_PENDING;
            $sale->contract_number = Util::generateNumberContract();
            $sale->total = 0;
            $sale->info_user = $infoUser;
            $sale->save();

            $plans = $input['plans'];
            $total = 0;
            $firstPaymentTotal = 0;
            foreach ($plans as $plan) {
                $pricePlanId = $plan['pricePlanId'] ?? $plan['id'];// TODO dejar solo pricePlanId
                $pricePlan = PlanPrice::query()->find($pricePlanId);

                $saleDetail = new SaleDetail();
                $saleDetail->sale_id = $sale->id;
                $saleDetail->plan_price_id = $pricePlan->id;
                $saleDetail->hospital_id = $plan['hospital_id'];
                $saleDetail->monthly_price = $pricePlan->monthly_price;
                $saleDetail->annual_price = $pricePlan->annual_price;
                $saleDetail->annual_with_discount = $pricePlan->annual_price_discount;
                $saleDetail->subscription_type = $plan['subscription_type'];

                if ($plan['subscription_type'] === 'ANNUAL') {
                    $total = $total + $pricePlan->annual_price;
                    $firstPaymentTotal = $firstPaymentTotal + $pricePlan->annual_price_discount;
                } else {
                    $total = $total + $pricePlan->monthly_price;
                    $firstPaymentTotal = $firstPaymentTotal + $pricePlan->monthly_price;
                }

                $saleDetail->save();
            }
            $sale->total = $total;
            $sale->first_payment_total = $firstPaymentTotal;
            $sale->save();

            DB::commit();
            return $sale;
        } catch (\Exception $e) {
            DB::rollBack();
            throw  $e;
        }
    }

    public function view($saleId)
    {
        return Sale::query()->find($saleId);
    }
}
