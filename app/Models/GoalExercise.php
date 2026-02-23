<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalExercise extends Model
{
    protected $table = 'goal_exercise';

    protected $fillable = [
        'goal_id',
        'exercise_id',
        'relevance_score',
    ];

    protected $casts = [
        'relevance_score' => 'float',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    // ─── Helper ──────────────────────────────────────────────

    // Label kategori relevansi
    public function getRelevanceLabelAttribute(): string
    {
        return match (true) {
            $this->relevance_score >= 1.0 => 'Sangat Cocok',
            $this->relevance_score >= 0.7 => 'Cocok',
            $this->relevance_score >= 0.3 => 'Netral',
            default                       => 'Tidak Direkomendasikan',
        };
    }
}
