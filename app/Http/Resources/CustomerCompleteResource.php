<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCompleteResource extends JsonResource
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
            'last_name' => $this->last_name,
            'document_type' => $this->document_type,
            'document' => $this->document,
            'email' => $this->email,
            "id" => $this->id,
            "photo_url" => $this->photo_url,
            "phone" => $this->phone,
            "direction" => $this->direction,
            "shirt_size" => $this->shirt_size ,
            "gender" => $this->gender ,
            "birth_date" => $this->birth_date ,
        ];
    }
}
