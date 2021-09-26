<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'productId' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'code' => $this->code,
            'description' => $this->description,
            'unit' => [
                'id' => $this->unit_id,
                'name' => $this->unit_id ? $this->unit->name : '',
                'values' => $this->unit_values ? explode(',', $this->unit_values) : null,
            ],
            'provider' => new ProviderResource($this->provider),
            'images' => ImageResource::collection($this->images),
            'categories' => BasicCategoryResource::collection($this->categories)
        ];
    }
}
