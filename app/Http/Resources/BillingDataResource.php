<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingDataResource extends JsonResource
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
            'name' => $this->name,
            'lastName' => $this->last_name,
            'documentType' => $this->document_type,
            'document' => $this->document,
            "direction" => $this->direction
        ];
    }
}
