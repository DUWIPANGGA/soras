<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationScoreBreakdown extends Model
{
    protected $fillable = [
        'recommendation_id',
        'exercise_id',
        'score_primary',
        'score_secondary',
        'score_goal',
        'score_bmi',
        'score_age',
    ];

    protected $casts = [
        'score_primary'   => 'float',
        'score_secondary' => 'float',
        'score_goal'      => 'float',
        'score_bmi'       => 'float',
        'score_age'       => 'float',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    // ─── Helper ──────────────────────────────────────────────

    // Hitung final score dari breakdown ini
    public function getFinalScoreAttribute(): float
    {
        return round(
            (0.30 * $this->score_primary)   +
                (0.20 * $this->score_secondary) +
                (0.25 * $this->score_goal)      +
                (0.15 * $this->score_bmi)       +
                (0.10 * $this->score_age),
            4
        );
    }
}
