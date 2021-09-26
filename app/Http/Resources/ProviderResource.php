<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'whatsapp' => 'https://api.whatsapp.com/send?phone=' . $this->country_code . $this->phone,
            'description' => $this->description,
            'category' => $this->category,
            'nameUser' => $this->user ? $this->user->name : null,
            'emailUser' => $this->user ? $this->user->email : null,
            'qualification' => (float)$this->qualification,
            'images' => ImageResource::collection($this->images),
            'categories' => $this->getCategories($this->id),
            'stores' => StoreResource::collection($this->stores)
        ];
    }
}
