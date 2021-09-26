<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use App\Transformers\SaleServiceTransformer;

class SaleService
{
    const accessToken = 'accessTokenSaleService';
    public function __construct()
    {
        $this->url = config('services.salesService.url');
        $this->urlAuth = config('services.registerCivil.urlAuth');
    }

    /**
     * @param $codeApplication
     * @param $codePlatform
     * @return array
     */
    protected function buildHeaderInformation()
    {
        return [
            'CodigoAplicacion' => config('services.salesService.applicationCode'),
            "CodigoPlataforma" => config('services.salesService.platformCode'),
            "SistemaOperativo" => config('services.salesService.operativeSystem'),
            "DispositivoNavegador" => config('services.salesService.browserDevice'),
            "DireccionIP" => Request::ip(),
        ];
    }

    /**
     * @return mixed|null
     */
    protected function generateToken()
    {
        if (Cache::has(self::accessToken)) {
            return Cache::get(self::accessToken);
        }
        $response = Http::asForm()->post("{$this->urlAuth}/oauth2/token",
            [
                "username" => config('services.salesService.username'),
                "password" => config('services.salesService.password'),
                'grant_type' => config('services.salesService.grandType'),
                'client_id' => config('services.salesService.clientId'),
            ]);
        $responseJson = $response->json();
        if ($response->successful()) {
            $accessToken = $responseJson['access_token'];
            $expiresIn = $responseJson['expires_in'];
            Cache::put(self::accessToken, $accessToken, $expiresIn - 500);
            return $accessToken;
        } else {
            return null;
        }
    }

    public function paymentHeaderEntry($sale)
    {
        $accessToken = $this->generateToken();
        if ($accessToken == null) {
            return [
                'code' => 500,
                'success' => false,
                'data' => [
                    "message" => "Not has token",
                ],
            ];
        }
        $payload = SaleServiceTransformer::paymentHeaderEntry($sale);
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->withToken("{$accessToken}")
            ->post(
                "{$this->url}/api/Pago/CreaPago",
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'payload' => $payload,
            'url' => "{$this->url}/api/Pago/CreaPago"
        ];
    }

    public function createPaymentDetail($sale)
    {
        $accessToken = $this->generateToken();
        if ($accessToken == null) {
            return [
                'code' => 500,
                'success' => false,
                'data' => [
                    "message" => "Not has token",
                ],
            ];
        }
        $payload = SaleServiceTransformer::createPaymentDetail($sale);
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->withToken("{$accessToken}")
            ->post(
                "{$this->url}/api/Pago/CreaDetallePago",
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'payload' => $payload,
            'url' => "{$this->url}/api/Pago/CreaDetallePago"
        ];
    }

    public function createPaymentMethod($sale)
    {
        $accessToken = $this->generateToken();
        if ($accessToken == null) {
            return [
                'code' => 500,
                'success' => false,
                'data' => [
                    "message" => "Not has token",
                ],
            ];
        }
        $payload = SaleServiceTransformer::createPaymentMethod($sale);
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->withToken("{$accessToken}")
            ->post(
                "{$this->url}/api/Pago/CreaFormaPago",
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'payload' => $payload,
            'url' => "{$this->url}/api/Pago/CreaFormaPago"
        ];
    }

}
