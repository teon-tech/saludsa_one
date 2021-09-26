<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'url' => $this->url,
            'type' => $this->imageParameter->name,
        ];
    }

}