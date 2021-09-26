<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicProviderResource extends JsonResource
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
            'providerId' => $this->id,
            'name' => $this->name,
            'owner' => $this->owner,
            'address' => $this->address,
            'code' => $this->code,
        ];
    }
}
