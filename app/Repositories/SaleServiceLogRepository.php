<?php

namespace App\Repositories;

use App\Models\SaleServiceLog;

class SaleServiceLogRepository
{

    public function save($response, $sale, $service, $id = null)
    {
        $saleService = $id == null ? new SaleServiceLog() : SaleServiceLog::find($id);
        $saleService->name = $service;
        $saleService->sale_id = $sale->id;
        $saleService->url = $response['url'];
        $saleService->payload = $response['payload'];
        $saleService->status_code = $response['data']['Estado'];
        $saleService->response = $response['data']['Datos'];
        $saleService->save();
    }

    public function saveError($response, $sale, $service, $id = null)
    {
        $saleService = $id === null ? new SaleServiceLog() : SaleServiceLog::find($id);
        $saleService->name = $service;
        $saleService->sale_id = $sale->id;
        $saleService->url = $response['url'];
        $saleService->payload = $response['payload'];
        $saleService->status_code = $response['data']['Estado'];
        $saleService->response = $response['data']['Mensajes'];
        $saleService->save();
    }

    public function saveSigmep($response, $sale, $id = null)
    {
        $saleService = $id == null ? new SaleServiceLog() : SaleServiceLog::find($id);
        $saleService->name = 'ServiceSigmep';
        $saleService->sale_id = $sale->id;
        $saleService->url = config('services.sigmedService.urlWsdl');
        $saleService->payload = $response['payload'];
        $saleService->status_code = $response['data']['state'];
        $saleService->response = $response['data'];
        $saleService->save();
    }

    public static function getPaymentNumber($sale){

        $service = SaleServiceLog::query()
        ->where('sale_id' , $sale->id)
        ->where('name' , 'paymentHeaderEntry')
        ->first();

        return $service->response['Numero'] ?? null;
    }
}
