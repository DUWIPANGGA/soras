<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'height_cm',
        'weight_kg',
        'bmi',
        'bmi_category',
        'age_category',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userComplaints()
    {
        return $this->hasMany(UserComplaint::class);
    }

    public function primaryComplaint()
    {
        return $this->hasOne(UserComplaint::class)
            ->where('is_primary', true)
            ->with('complaint');
    }

    public function secondaryComplaints()
    {
        return $this->hasMany(UserComplaint::class)
            ->where('is_primary', false)
            ->with('complaint');
    }

    public function userGoals()
    {
        return $this->hasMany(UserGoal::class)->with('goal');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class)
            ->orderByDesc('created_at');
    }

    // ─── Helper Methods ──────────────────────────────────────

    // Hitung BMI dari tinggi & berat
    public function calculateBmi(): float
    {
        $heightM = $this->height_cm / 100;
        return round($this->weight_kg / ($heightM * $heightM), 2);
    }

    // Kategorikan BMI (standar WHO)
    public function categorizeBmi(float $bmi): string
    {
        return match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi < 25.0 => 'Normal',
            $bmi < 30.0 => 'Overweight',
            default     => 'Obesitas',
        };
    }

    // Kategorikan usia
    public function categorizeAge(int $age): string
    {
        return match (true) {
            $age < 13  => 'Anak',
            $age < 18  => 'Remaja',
            $age < 50  => 'Dewasa',
            default    => 'Lansia',
        };
    }
}
