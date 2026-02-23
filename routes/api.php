<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecommendationController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────

// Auth — rate limited anti brute force (5/menit)
Route::prefix('auth')->middleware('throttle:auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Master data — bebas akses, tidak perlu rate limit ketat
Route::get('complaints', [ProfileController::class, 'complaints']);
Route::get('goals',      [ProfileController::class, 'goals']);

// ─── Protected Routes ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/',  [ProfileController::class, 'show']);
        Route::post('/', [ProfileController::class, 'store']);
        Route::put('/',  [ProfileController::class, 'update']);
    });

    // Recommendation — rate limited 10/menit
    Route::prefix('recommendations')
        ->middleware('throttle:recommendation')
        ->group(function () {
            Route::post('/',    [RecommendationController::class, 'generate']);
            Route::get('/{id}', [RecommendationController::class, 'show']);
        });

    // History — tidak perlu rate limit ketat
    Route::get('history', [HistoryController::class, 'index']);
});
