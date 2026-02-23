<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationDetail extends Model
{
    protected $fillable = [
        'recommendation_id',
        'exercise_id',
        'score',
        'rank_order',
    ];

    protected $casts = [
        'score'      => 'float',
        'rank_order' => 'integer',
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

    public function scoreBreakdown()
    {
        return $this->hasOne(
            RecommendationScoreBreakdown::class,
            'exercise_id',        // FK di score_breakdowns
            'exercise_id'         // local key di details
        )->whereColumn(
            'recommendation_score_breakdowns.recommendation_id',
            'recommendation_details.recommendation_id'
        );
    }
}
