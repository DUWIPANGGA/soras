<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreBreakdownResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'score_primary'   => $this->score_primary,
            'score_secondary' => $this->score_secondary,
            'score_goal'      => $this->score_goal,
            'score_bmi'       => $this->score_bmi,
            'score_age'       => $this->score_age,
        ];
    }
}
