<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_profile_id',
        'primary_complaint_id',
        'goal_id',
        'final_score',
        'confidence',
    ];

    protected $casts = [
        'final_score' => 'float',
        'confidence'  => 'float',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function primaryComplaint()
    {
        return $this->belongsTo(Complaint::class, 'primary_complaint_id');
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    // Detail top-3 rekomendasi, diurutkan by rank
    public function details()
    {
        return $this->hasMany(RecommendationDetail::class)
            ->orderBy('rank_order');
    }

    // Breakdown skor per faktor (transparansi akademik)
    public function scoreBreakdowns()
    {
        return $this->hasMany(RecommendationScoreBreakdown::class);
    }

    // ─── Helper ──────────────────────────────────────────────

    // Ambil rekomendasi #1
    public function topRecommendation()
    {
        return $this->details()->where('rank_order', 1)->first();
    }
}
