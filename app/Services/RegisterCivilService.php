<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cache;

class RegisterCivilService
{
    const accessToken = 'accessTokenRegisterCivil';
    public function __construct()
    {
        $this->urlAuth = config('services.registerCivil.urlAuth');
        $this->urlFindUser = config('services.registerCivil.urlFindUser');
    }

    /**
     * @param $codeApplication
     * @param $codePlatform
     * @return array
     */
    protected function buildHeaderInformation($codeApplication, $codePlatform)
    {
        return [
            'CodigoAplicacion' => $codeApplication,
            "CodigoPlataforma" => $codePlatform,
            "SistemaOperativo" => config('services.registerCivil.operativeSystem'),
            "DispositivoNavegador" => config('services.registerCivil.browserDevice'),
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
                "username" => config('services.registerCivil.username'),
                "password" => config('services.registerCivil.password'),
                'grant_type' => config('services.registerCivil.grandType'),
                'client_id' => config('services.registerCivil.clientId'),
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

    public function findUserByCI($numeroCedula)
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

        $response = Http::withHeaders($this->buildHeaderInformation(config('services.registerCivil.applicationCode'), config('services.registerCivil.platformCode')))
            ->withToken("{$accessToken}")
            ->get(
                $this->urlFindUser,
                [
                    'numeroCedula' => $numeroCedula,
                ]
            );

        return $response->json();
    }
}
