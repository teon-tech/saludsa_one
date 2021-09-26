<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'subtitle' => $this->subtitle,
            'default' => $this->default == 'YES',
            'colorPrimary' => $this->color_primary,
            'colorSecondary' => $this->color_secondary,
            'description' => $this->description,
            'keywords' => isset($this->keywords) ? explode(',', $this->keywords) : null,
            'type' => new TypePlanResource($this->typePlan),
            'coverages' => CoverageResource::collection($this->coverages),
            'sections' => SectionResource::collection($this->sections->sortBy('weight')),
            'questions' => QuestionResource::collection($this->questions->sortBy('weight')),
            'images' => ImageResource::collection($this->images),
            'files' => FileResource::collection($this->files)
        ];
    }
}
