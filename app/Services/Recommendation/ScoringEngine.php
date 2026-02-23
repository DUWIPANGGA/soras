<?php

namespace App\Services\Recommendation;

use App\Models\Exercise;
use Illuminate\Support\Collection;

class ScoringEngine
{
    // ─── Bobot Faktor ─────────────────────────────────────────────

    const WEIGHT_PRIMARY   = 0.30;
    const WEIGHT_SECONDARY = 0.20;
    const WEIGHT_GOAL      = 0.25;
    const WEIGHT_BMI       = 0.15;
    const WEIGHT_AGE       = 0.10;

    // ─── Constructor Injection ────────────────────────────────────

    public function __construct(
        private readonly BMICalculator $bmiCalculator,
        private readonly AgeClassifier $ageClassifier,
    ) {}

    // ─── Main Method ──────────────────────────────────────────────

    /**
     * Hitung skor semua exercise dan urutkan dari tertinggi.
     *
     * @param Collection  $exercises         — hasil HardFilter
     * @param object      $primaryComplaint  — Complaint model
     * @param Collection  $secondaryComplaints — Collection of Complaint model
     * @param object      $goal             — Goal model
     * @param string      $bmiCategory      — hasil BMICalculator
     * @param string      $ageCategory      — hasil AgeClassifier
     *
     * @return Collection — exercise + score + breakdown, sorted desc
     */
    public function calculate(
        Collection $exercises,
        object     $primaryComplaint,
        Collection $secondaryComplaints,
        object     $goal,
        string     $bmiCategory,
        string     $ageCategory,
    ): Collection {
        return $exercises
            ->map(function (Exercise $exercise) use (
                $primaryComplaint,
                $secondaryComplaints,
                $goal,
                $bmiCategory,
                $ageCategory,
            ) {
                // Hitung masing-masing faktor
                $scorePrimary   = $this->scorePrimary($exercise, $primaryComplaint);
                $scoreSecondary = $this->scoreSecondary($exercise, $secondaryComplaints);
                $scoreGoal      = $this->scoreGoal($exercise, $goal);
                $scoreBmi       = $this->scoreBmi($exercise, $bmiCategory);
                $scoreAge       = $this->scoreAge($exercise, $ageCategory);

                // Hitung raw score
                $rawScore = (self::WEIGHT_PRIMARY   * $scorePrimary)
                          + (self::WEIGHT_SECONDARY * $scoreSecondary)
                          + (self::WEIGHT_GOAL      * $scoreGoal)
                          + (self::WEIGHT_BMI       * $scoreBmi)
                          + (self::WEIGHT_AGE       * $scoreAge);

                // Clamp: pastikan tidak negatif
                $finalScore = max(0, round($rawScore, 4));

                // Return exercise + semua data skor
                return [
                    'exercise'         => $exercise,
                    'final_score'      => $finalScore,
                    'breakdown'        => [
                        'score_primary'   => round($scorePrimary, 4),
                        'score_secondary' => round($scoreSecondary, 4),
                        'score_goal'      => round($scoreGoal, 4),
                        'score_bmi'       => round($scoreBmi, 4),
                        'score_age'       => round($scoreAge, 4),
                    ],
                ];
            })
            ->sortByDesc('final_score')
            ->values(); // reset index setelah sort
    }

    // ─── Scorer Per Faktor ────────────────────────────────────────

    /**
     * P — Skor Primary Complaint (bobot 0.30)
     *
     * Ambil relevance_score dari pivot complaint_exercise
     * berdasarkan primary complaint user.
     */
    private function scorePrimary(
        Exercise $exercise,
        object   $primaryComplaint,
    ): float {
        $pivot = $exercise->complaints
            ->where('id', $primaryComplaint->id)
            ->first();

        return $pivot ? (float) $pivot->pivot->relevance_score : 0.0;
    }

    /**
     * S — Skor Secondary Complaints (bobot 0.20)
     *
     * Rata-rata relevance_score dari semua secondary complaints.
     * Kalau tidak ada secondary complaint, return 0.0.
     */
    private function scoreSecondary(
        Exercise   $exercise,
        Collection $secondaryComplaints,
    ): float {
        if ($secondaryComplaints->isEmpty()) {
            return 0.0;
        }

        $scores = $secondaryComplaints->map(function ($complaint) use ($exercise) {
            $pivot = $exercise->complaints
                ->where('id', $complaint->id)
                ->first();

            return $pivot ? (float) $pivot->pivot->relevance_score : 0.0;
        });

        return round($scores->avg(), 4);
    }

    /**
     * G — Skor Goal (bobot 0.25)
     *
     * Ambil relevance_score dari pivot goal_exercise
     * berdasarkan goal yang dipilih user.
     */
    private function scoreGoal(
        Exercise $exercise,
        object   $goal,
    ): float {
        $pivot = $exercise->goals
            ->where('id', $goal->id)
            ->first();

        return $pivot ? (float) $pivot->pivot->relevance_score : 0.0;
    }

    /**
     * BMI — Skor BMI (bobot 0.15)
     *
     * Ambil skor dari matriks BMI di BMICalculator
     * berdasarkan kategori BMI user.
     */
    private function scoreBmi(
        Exercise $exercise,
        string   $bmiCategory,
    ): float {
        return $this->bmiCalculator->getScore(
            $bmiCategory,
            $exercise->name,
        );
    }

    /**
     * Age — Skor Usia + Impact (bobot 0.10)
     *
     * Ambil skor dari matriks AgeImpact di AgeClassifier
     * berdasarkan kategori usia dan impact level exercise.
     */
    private function scoreAge(
        Exercise $exercise,
        string   $ageCategory,
    ): float {
        return $this->ageClassifier->getScore(
            $ageCategory,
            $exercise->impact_level,
        );
    }

    // ─── Helper Methods ───────────────────────────────────────────

    /**
     * Hitung confidence score.
     *
     * Confidence = (TopScore / SumAllScores) × 100
     * Menunjukkan seberapa yakin sistem terhadap rekomendasi #1.
     */
    public function calculateConfidence(Collection $scoredExercises): float
    {
        $topScore = $scoredExercises->first()['final_score'] ?? 0;
        $sumAll   = $scoredExercises->sum('final_score');

        if ($sumAll === 0) {
            return 0.0;
        }

        return round(($topScore / $sumAll) * 100, 2);
    }

    /**
     * Ambil top N exercise setelah scoring.
     * Default top 3 sesuai spesifikasi SORAS.
     */
    public function getTopN(Collection $scoredExercises, int $n = 3): Collection
    {
        return $scoredExercises->take($n);
    }
}