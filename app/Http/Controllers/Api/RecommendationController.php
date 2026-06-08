<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recommendation\GenerateRecommendationRequest;
use App\Http\Resources\RecommendationResource;
use App\Services\Recommendation\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly RecommendationService $service,
    ) {}

    // POST /api/recommendations
    public function generate(GenerateRecommendationRequest $request): JsonResponse
    {
        // Pastikan user sudah punya profil
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi profil terlebih dahulu sebelum mendapatkan rekomendasi.',
            ], 422);
        }

        $result = $this->service->recommend(
            userProfileId: $profile->id,
            primaryComplaintId: $request->primary_complaint_id,
            secondaryComplaintIds: $request->secondary_complaint_ids ?? [],
            goalId: $request->goal_id,
        );

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi berhasil dibuat.',
            'data'    => new RecommendationResource($result),
        ], 201);
    }

    // GET /api/recommendations/{id}
    public function show(Request $request, int $id): JsonResponse
    {
        $recommendation = $this->service->getDetail($id);

        // Pastikan rekomendasi milik user ini
        if ($recommendation->userProfile->user_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada akses ke rekomendasi ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => new RecommendationResource($recommendation),
        ]);
    }
}
