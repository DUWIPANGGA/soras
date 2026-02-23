<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'rank'           => $this->rank_order,
            'score'          => $this->score,
            'exercise'       => new ExerciseResource(
                $this->whenLoaded('exercise')
            ),
            'score_breakdown' => new ScoreBreakdownResource(
                $this->whenLoaded('scoreBreakdown')
            ),
        ];
    }
}
