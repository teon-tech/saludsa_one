<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'saleId' => $this->id,
            'total' => (float)$this->total,
            'contractNumber' => $this->contract_number,
            'customer' => new CustomerResource($this->customerData),
            'billingData' => new BillingDataResource($this->billingData),
            'province' => new ProvinceResource($this->province),
            'details' => SaleDetailResource::collection($this->details)
        ];
    }
}
