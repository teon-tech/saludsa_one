<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoverageResource extends JsonResource
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
            'type' => new TypeCoverageResource($this->typeCoverage),
            'name' => $this->name,
            'description' => $this->description,
            'images' => ImageResource::collection($this->images)
        ];
    }
}
