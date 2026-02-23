<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendationResource;
use App\Services\Recommendation\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(
        private readonly RecommendationService $service,
    ) {}

    // GET /api/history
    public function index(Request $request): JsonResponse
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Belum ada riwayat rekomendasi.',
            ]);
        }

        $history = $this->service->getHistory($profile->id);

        return response()->json([
            'success' => true,
            'data'    => RecommendationResource::collection($history),
            'total'   => $history->count(),
        ]);
    }
}
