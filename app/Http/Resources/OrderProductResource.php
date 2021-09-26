<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
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
            'product' => new BasicProductResource($this->product),
            'price' => (float)$this->price,
            'quantity' => $this->quantity,
            'tax' => (float)$this->tax,
            'tax_calculated' => (float)$this->tax_calculated,
            'subtotal' => (float)$this->subtotal,
            'total' => (float)$this->total,
            'unit' => $this->unit_selected
        ];
    }
}
