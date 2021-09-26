<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class KushkiService
{

    public function __construct()
    {
        $this->url = config('services.kushki.url');
    }

    /**
     * @param $codeApplication
     * @param $codePlatform
     * @return array
     */
    protected function buildHeaderInformation()
    {
        return [
            'Private-Merchant-Id' => config('services.kushki.privateMerchantId'),
        ];
    }

    public function subscriptions($payload)
    {
        $url = $this->url;
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->post(
                $url,
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'url' => "POST: {$url}"
        ];
    }

    public function temporaryDiscount($subscriptionId, $payload)
    {
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->put(
                $this->url . '/' . $subscriptionId,
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
        ];
    }

    public function firstSubscriptionPayment($subscriptionId, $payload)
    {
        $url = "{$this->url}/{$subscriptionId}";
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->post(
                $url,
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'url' => "POST: {$url}"
        ];
    }

    public function updateSubscription($subscriptionId, $payload)
    {
        $url = "{$this->url}/{$subscriptionId}";
        $response = Http::withHeaders($this->buildHeaderInformation())
            ->PATCH(
                "{$this->url}/{$subscriptionId}",
                $payload
            );
        return [
            'data' => $response->json(),
            'status' => $response->status(),
            'url' => "PATCH: {$url}"
        ];
    }
}
