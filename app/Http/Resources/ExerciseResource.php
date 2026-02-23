<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'category'            => $this->category,
            'impact_level'        => $this->impact_level,
            'impact_label'        => $this->impact_label,
            'intensity_level'     => $this->intensity_level,
            'duration_min'        => $this->duration_min,
            'frequency_per_week'  => $this->frequency_per_week,
            'description'         => $this->description,
        ];
    }
}
