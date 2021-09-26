<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
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
            'price' => (float)$this->price,
            'subscriptionType' => $this->subscription_type,
            'plan' => new BasicPlanResource($this->plan),
            'hospital' => new HospitalResource($this->hospital)
        ];
    }
}
