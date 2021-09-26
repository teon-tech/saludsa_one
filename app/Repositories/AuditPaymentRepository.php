<?php


namespace App\Repositories;


use App\Models\AuditPayment;

class AuditPaymentRepository
{
    /**
     * @param $customerId
     * @param $saleId
     * @param $url
     * @param $payload
     * @param $statusCode
     * @param $response
     */
    public function save($customerId, $saleId, $url, $payload, $statusCode, $response)
    {
        $audit = new AuditPayment();
        $audit->customer_id = $customerId;
        $audit->sale_id = $saleId;
        $audit->url = $url;
        $audit->payload = $payload;
        $audit->status_code = $statusCode;
        $audit->response = $response;
        $audit->save();
    }

}