<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanWithPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pricePlanId' => $this->id,
            'annualPrice' => $this->annual_price,
            'monthlyPrice' => $this->monthly_price,
            'price' => $this->annual_price,
            'annualPriceWithDiscount' => $this->annual_price_discount,
            'labelDiscount' => $this->label_discount,
            'plan' => new PlanResource($this->plan),
        ];
    }
}
