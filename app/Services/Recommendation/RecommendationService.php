<?php

namespace App\Services\Recommendation;

use App\Models\Complaint;
use App\Models\Exercise;
use App\Models\Goal;
use App\Models\Recommendation;
use App\Models\RecommendationDetail;
use App\Models\RecommendationScoreBreakdown;
use App\Models\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    // ─── Constructor Injection ────────────────────────────────────

    public function __construct(
        private readonly BMICalculator $bmiCalculator,
        private readonly AgeClassifier $ageClassifier,
        private readonly HardFilter    $hardFilter,
        private readonly ScoringEngine $scoringEngine,
    ) {}

    // ─── Main Method ──────────────────────────────────────────────

    /**
     * Jalankan seluruh pipeline rekomendasi dan simpan ke DB.
     *
     * @param int   $userProfileId
     * @param int   $primaryComplaintId
     * @param int[] $secondaryComplaintIds
     * @param int   $goalId
     *
     * @return Recommendation — lengkap dengan details & breakdowns
     */
    public function recommend(
        int   $userProfileId,
        int   $primaryComplaintId,
        array $secondaryComplaintIds,
        int   $goalId,
    ): Recommendation {
        return DB::transaction(function () use (
            $userProfileId,
            $primaryComplaintId,
            $secondaryComplaintIds,
            $goalId,
        ) {
            // ── Step 1: Load data master ──────────────────────────

            $userProfile = UserProfile::findOrFail($userProfileId);

            $primaryComplaint = Complaint::findOrFail($primaryComplaintId);

            $secondaryComplaints = Complaint::whereIn('id', $secondaryComplaintIds)
                ->get();

            $goal = Goal::findOrFail($goalId);

            // ── Step 2: Hitung & kategorikan BMI ─────────────────

            $bmiResult = $this->bmiCalculator->process(
                $userProfile->height_cm,
                $userProfile->weight_kg,
            );

            // ── Step 3: Kategorikan usia ──────────────────────────

            $ageCategory = $this->ageClassifier->categorize(
                $userProfile->age,
            );

            // ── Step 4: Update derived fields di UserProfile ──────

            $userProfile->update([
                'bmi'          => $bmiResult['bmi'],
                'bmi_category' => $bmiResult['category'],
                'age_category' => $ageCategory,
            ]);

            // ── Step 5: Load semua exercise dengan pivot ──────────

            $allExercises = Exercise::with(['complaints', 'goals'])->get();

            // ── Step 6: Hard Filter ───────────────────────────────

            $filteredExercises = $this->hardFilter->filter(
                exercises: $allExercises,
                primarySlug: $primaryComplaint->slug,
                secondarySlugs: $secondaryComplaints
                    ->pluck('slug')
                    ->toArray(),
                bmiCategory: $bmiResult['category'],
                ageCategory: $ageCategory,
            );

            // ── Step 7: Scoring Engine ────────────────────────────

            $scoredExercises = $this->scoringEngine->calculate(
                exercises: $filteredExercises,
                primaryComplaint: $primaryComplaint,
                secondaryComplaints: $secondaryComplaints,
                goal: $goal,
                bmiCategory: $bmiResult['category'],
                ageCategory: $ageCategory,
            );

            // ── Step 8: Confidence & Top 3 ────────────────────────

            $confidence = $this->scoringEngine->calculateConfidence(
                $scoredExercises,
            );

            $top3 = $this->scoringEngine->getTopN($scoredExercises, 3);

            // ── Step 9: Simpan ke database ────────────────────────

            return $this->saveRecommendation(
                userProfile: $userProfile,
                primaryComplaint: $primaryComplaint,
                goal: $goal,
                top3: $top3,
                allScored: $scoredExercises,
                confidence: $confidence,
            );
        });
    }

    // ─── Database Persistence ─────────────────────────────────────

    /**
     * Simpan hasil rekomendasi ke 3 tabel sekaligus.
     */
    private function saveRecommendation(
        UserProfile $userProfile,
        object      $primaryComplaint,
        object      $goal,
        Collection  $top3,
        Collection  $allScored,
        float       $confidence,
    ): Recommendation {
        // Simpan header rekomendasi
        $recommendation = Recommendation::create([
            'user_profile_id'      => $userProfile->id,
            'primary_complaint_id' => $primaryComplaint->id,
            'goal_id'              => $goal->id,
            'final_score'          => $top3->first()['final_score'] ?? 0,
            'confidence'           => $confidence,
        ]);

        // Simpan top 3 detail + breakdown
        $top3->each(function ($item, $index) use ($recommendation) {
            $rankOrder = $index + 1;

            RecommendationDetail::create([
                'recommendation_id' => $recommendation->id,
                'exercise_id'       => $item['exercise']->id,
                'score'             => $item['final_score'],
                'rank_order'        => $rankOrder,
            ]);

            RecommendationScoreBreakdown::create([
                'recommendation_id' => $recommendation->id,
                'exercise_id'       => $item['exercise']->id,
                'score_primary'     => $item['breakdown']['score_primary'],
                'score_secondary'   => $item['breakdown']['score_secondary'],
                'score_goal'        => $item['breakdown']['score_goal'],
                'score_bmi'         => $item['breakdown']['score_bmi'],
                'score_age'         => $item['breakdown']['score_age'],
            ]);
        });

        // Load details & exercise dulu
        $recommendation->load(['details.exercise', 'primaryComplaint', 'goal']);

        // Load semua breakdowns sekaligus
        $breakdowns = RecommendationScoreBreakdown::where(
            'recommendation_id',
            $recommendation->id
        )->get()->keyBy('exercise_id');

        // Inject breakdown ke masing-masing detail secara manual
        $recommendation->details->each(function (RecommendationDetail $detail) use ($breakdowns) {
            $detail->setRelation(
                'scoreBreakdown',
                $breakdowns->get($detail->exercise_id)
            );
        });

        return $recommendation;
    }

    // ─── Helper Public Methods ────────────────────────────────────

    /**
     * Ambil riwayat rekomendasi user.
     */
    public function getHistory(int $userProfileId): Collection
    {
        return Recommendation::where('user_profile_id', $userProfileId)
            ->with([
                'details.exercise',
                'details.scoreBreakdown',
                'primaryComplaint',
                'goal',
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Ambil detail satu rekomendasi.
     */
    public function getDetail(int $recommendationId): Recommendation
    {
        return Recommendation::with([
            'details.exercise',
            'details.scoreBreakdown',
            'primaryComplaint',
            'goal',
            'userProfile',
        ])->findOrFail($recommendationId);
    }
}
