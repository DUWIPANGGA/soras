<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'age'          => $this->age,
            'gender'       => $this->gender,
            'height_cm'    => $this->height_cm,
            'weight_kg'    => $this->weight_kg,
            'bmi'          => $this->bmi,
            'bmi_category' => $this->bmi_category,
            'age_category' => $this->age_category,
        ];
    }
}
