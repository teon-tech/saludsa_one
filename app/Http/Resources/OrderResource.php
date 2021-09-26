<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'orderId' => $this->id,
            'provider' => new ProviderResource($this->provider),
            'subtotal' => (float)$this->subtotal,
            'tax' => (float)$this->tax,
            'taxCalculated' => (float)$this->tax_calculated,
            'total' => (float)$this->total,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'qualification' => $this->qualification,
            'details' => OrderProductResource::collection($this->details)
        ];
    }
}
