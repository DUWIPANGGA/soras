<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Goal;
use App\Services\Recommendation\RecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly RecommendationService $service,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        // Paksa isi profil dulu
        if (!$request->user()->profile) {
            return redirect()
                ->route('profile.index')
                ->with('warning', 'Lengkapi profil terlebih dahulu.');
        }

        $complaints = Complaint::orderBy('name')->get();
        $goals      = Goal::orderBy('name')->get();

        return view('pages.recommendation.create', compact(
            'complaints',
            'goals',
        ));
    }

    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'primary_complaint_id'    => ['required', 'integer', 'exists:complaints,id'],
            'secondary_complaint_ids' => ['nullable', 'array', 'max:3'],
            'secondary_complaint_ids.*' => [
                'integer',
                'exists:complaints,id',
            ],
            'goal_id' => ['required', 'integer', 'exists:goals,id'],
        ]);

        // Validasi manual secondary
        $primaryId    = (int) $validated['primary_complaint_id'];
        $secondaryIds = array_map('intval', $validated['secondary_complaint_ids'] ?? []);

        if (in_array($primaryId, $secondaryIds)) {
            return back()
                ->withErrors(['secondary_complaint_ids' => 'Keluhan tambahan tidak boleh sama dengan keluhan utama.'])
                ->withInput();
        }

        if (count($secondaryIds) !== count(array_unique($secondaryIds))) {
            return back()
                ->withErrors(['secondary_complaint_ids' => 'Keluhan tambahan tidak boleh duplikat.'])
                ->withInput();
        }

        $result = $this->service->recommend(
            userProfileId: $request->user()->profile->id,
            primaryComplaintId: $primaryId,
            secondaryComplaintIds: $secondaryIds,
            goalId: (int) $validated['goal_id'],
        );

        return redirect()->route('recommendation.result', $result->id);
    }

    public function result(Request $request, int $id): View|RedirectResponse
    {
        $recommendation = $this->service->getDetail($id);

        // Pastikan milik user ini
        if ($recommendation->userProfile->user_id != $request->user()->id) {
            abort(403);
        }

        return view('pages.recommendation.result', compact('recommendation'));
    }
}
