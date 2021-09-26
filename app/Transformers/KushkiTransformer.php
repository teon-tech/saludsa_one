<?php


namespace App\Transformers;


use App\Models\Sale;
use Carbon\Carbon;

class KushkiTransformer
{

    /**
     * @param $tokenKushki
     * @param Sale $sale
     * @return array
     */
    public function buildSubscription($tokenKushki, Sale $sale): array
    {
        $details = $sale->details;
        $detail = $details[0];
        $customer = $sale->customer;

        $periodicity = 'custom';
        return [
            "token" => $tokenKushki,
            "planName" => $detail->planPrice->plan->name,
            "periodicity" => $periodicity,
            "contactDetails" => [
                "documentType" => $customer['document_type'],
                "documentNumber" => $customer['document'],
                "email" => $customer['email'],
                "firstName" => $customer['name'],
                "lastName" => $customer['father_last_name'],
                "phoneNumber" => $customer['phone'],
            ],
            "startDate" => Carbon::now()->addDay()->format('Y-m-d'),
            "amount" => [
                "subtotalIva" => 0,
                "subtotalIva0" => 0,
                "ice" => 0,
                "iva" => 0,
                "currency" => "USD",
            ],
            "metadata" => [
                "saleId" => $sale->id,
                "contractNumber" => $sale->contract_number,
            ],
        ];
    }

    /**
     * @param $tokenKushki
     * @param Sale $sale
     * @return array
     */
    public function buildFirstPayment($tokenKushki, Sale $sale): array
    {
        $detail = $sale->details[0];
        $type = $detail->subscription_type;

        if ($type == 'ANNUAL') {
            $amount = (float)$detail->annual_with_discount;
        } else {
            $amount = (float)$detail->monthly_price;
        }

        return [
            "language" => "es",
            "token" => $tokenKushki,
            "amount" => [
                "subtotalIva" => 0,
                "subtotalIva0" => $amount,
                "ice" => 0,
                "iva" => 0,
                "currency" => "USD",
            ],
            "metadata" => [
                "saleId" => $sale->id,
                "contractNumber" => $sale->contract_number,
                "firstPayment" => true,
            ],
            "contactDetails" => [
                "documentType" => self::getDocumentType($sale->customer['document_type']),
                "documentNumber" => $sale->customer['document'],
                "email" => $sale->customer['email'],
                "firstName" => $sale->customer['name'],
                "lastName" => $sale->customer['father_last_name'],

            ],
            "orderDetails" => [
                "shippingDetails" => [
                    "name" => $sale->customer['name'] . '' . $sale->customer['father_last_name'],
                    "phone" => $sale->customer['phone'],
                    "address" => $sale->customer['direction'],
                    "city" => "Quito",
                    "region" => $sale->province->region['name'],
                    "country" => "Ecuador",
                ],
                "billingDetails" => [
                    "name" => $sale->billingData['name'] . '' . $sale->billingData['last_name'],
                    "phone" => $sale->customer['phone'],
                    "address" => $sale->billingData['direction'],
                    //"city" => "Quito",7
                    "region" => $sale->province->region['name'],
                    "country" => "Ecuador",
                ],
            ],
            "productDetails" => [
                "product" => [
                    [
                        "id" => $detail->planPrice->plan['code'],
                        "title" => $detail->planPrice->plan['name'],
                        "price" => $amount,
                        "sku" => $detail->planPrice->plan['code'],
                        "quantity" => 1,
                    ],
                ],
            ],
            "fullResponse" => true,
        ];
    }

    /**
     * @param Sale $sale
     * @return array
     */
    public function buildUpdatePeriodicity(Sale $sale): array
    {
        $detail = $sale->details[0];
        $type = $detail->subscription_type;
        $currentDate = Carbon::now();
        if ($type === 'MONTHLY') {
            $periodicity = 'monthly';
            $amount = (float)$detail->monthly_price;
            $startDate = $currentDate->addMonth();
        } else {
            $periodicity = 'yearly';
            $amount = (float)$detail->annual_price;
            $startDate = $currentDate->addYear();
        }

        // Comment to production
        $startDate = Carbon::now()->addDay();


        return [
            "periodicity" => $periodicity,
            "amount" => [
                "subtotalIva" => 0,
                "subtotalIva0" => $amount,
                "ice" => 0,
                "iva" => 0,
                "currency" => "USD",
            ],
            "startDate" => $startDate->format('Y-m-d'),
        ];
    }

    /**
     * @param $documentType
     * @return string
     */
    public static function getDocumentType($documentType)
    {
        $result = 'CC';
        switch ($documentType) {
            case 'PASSPORT':
                $result = 'PP';
                break;
            case 'RUC':
                $result = 'NIT';
                break;
            default:
                # CI
                $result = 'CC';
                break;
        }

        return $result;
    }

}