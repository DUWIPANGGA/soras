<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'impact_level',
        'intensity_level',
        'duration_min',
        'frequency_per_week',
        'description',
    ];

    // Konstanta untuk readability
    const IMPACT_LOW    = 1;
    const IMPACT_MEDIUM = 2;
    const IMPACT_HIGH   = 3;

    // Relasi ke keluhan lewat pivot complaint_exercise
    public function complaints()
    {
        return $this->belongsToMany(Complaint::class, 'complaint_exercise')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }

    // Relasi ke goals lewat pivot goal_exercise
    public function goals()
    {
        return $this->belongsToMany(Goal::class, 'goal_exercise')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }

    // Helper: apakah olahraga ini high impact?
    public function isHighImpact(): bool
    {
        return $this->impact_level === self::IMPACT_HIGH;
    }

    // Helper: label impact level
    public function getImpactLabelAttribute(): string
    {
        return match ($this->impact_level) {
            self::IMPACT_LOW    => 'Low',
            self::IMPACT_MEDIUM => 'Medium',
            self::IMPACT_HIGH   => 'High',
            default             => 'Unknown',
        };
    }
}
