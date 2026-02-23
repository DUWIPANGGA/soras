<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'final_score'       => $this->final_score,
            'confidence'        => $this->confidence,
            'confidence_label'  => $this->getConfidenceLabel(),
            'primary_complaint' => new ComplaintResource(
                $this->whenLoaded('primaryComplaint')
            ),
            'goal'              => new GoalResource(
                $this->whenLoaded('goal')
            ),
            'recommendations'   => RecommendationDetailResource::collection(
                $this->whenLoaded('details')
            ),
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }

    // Label confidence untuk UI
    private function getConfidenceLabel(): string
    {
        return match (true) {
            $this->confidence >= 40 => 'Sangat Yakin',
            $this->confidence >= 30 => 'Yakin',
            $this->confidence >= 20 => 'Cukup Yakin',
            default                 => 'Perlu Evaluasi',
        };
    }
}
