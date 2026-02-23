<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ComplaintExercise extends Pivot
{
    protected $table = 'complaint_exercise';

    protected $fillable = [
        'complaint_id',
        'exercise_id',
        'relevance_score',
    ];

    protected $casts = [
        'relevance_score' => 'float',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function isRisky(): bool
    {
        return $this->relevance_score < 0;
    }

    public function getRelevanceLabelAttribute(): string
    {
        return match (true) {
            $this->relevance_score >= 1.0 => 'Sangat Cocok',
            $this->relevance_score >= 0.7 => 'Cocok',
            $this->relevance_score >= 0.3 => 'Netral',
            $this->relevance_score >= 0.0 => 'Tidak Direkomendasikan',
            default                       => 'Berisiko',
        };
    }
}
