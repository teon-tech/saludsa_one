<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use App\Transformers\SigmepTransformer;
use Illuminate\Support\Facades\Log;

class SigmepService
{
    
    public function __construct()
    {
        $this->urlWsdl = config('services.sigmedService.urlWsdl');
    }

    public function createContract($sale): array
    {
        $return = null;
        try {
            /* $context = stream_context_create([
                    'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]); */
            $payload = SigmepTransformer::buildStructure($sale);
            $client = new \SoapClient($this->urlWsdl, [
                //'stream_context' => $context,
                //'trace' => 1, 'exceptions' => 0
            ]);
            
            $response = $client->crearContratoSigmep($payload);
            if (isset($response->crearContratoSigmepResult)) {
                $json = json_encode($response->crearContratoSigmepResult);
                $responseArray = json_decode($json, true);
                $return = [
                    'status' => 200,
                    'message' => 'Success get data from sigmed',
                    'data' => $responseArray,
                    'payload' => $payload,
                ];
            } else {
                $json = json_encode($response);
                $responseArray = json_decode($json, true);
                $return = [
                    'status' => 500,
                    'message' => 'Error get from Sigmep',
                    'data' => $responseArray,
                    'payload' => $payload,
                ];
            }

        } catch (\Exception $e) {
            $return = [
                'success' => true,
                'message' => 'Error to soap sigmed: ' . $e->getMessage(),
                'data' => null,
                'payload' => $payload,
            ];
        }
        Log::info('Sigmep Soap', $return);
        return $return;
    }

}
