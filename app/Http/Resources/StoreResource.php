<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'cityId' => $this->city_id,
            'cityName'  => $this->city->name,
            'name' => $this->name,
            'address' => $this->address
        ];
    }
}
