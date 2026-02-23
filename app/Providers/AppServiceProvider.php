<?php

namespace App\Providers;

use App\Services\Recommendation\AgeClassifier;
use App\Services\Recommendation\BMICalculator;
use App\Services\Recommendation\HardFilter;
use App\Services\Recommendation\RecommendationService;
use App\Services\Recommendation\ScoringEngine;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Service bindings yang sudah ada sebelumnya
        $this->app->singleton(BMICalculator::class);
        $this->app->singleton(AgeClassifier::class);
        $this->app->singleton(HardFilter::class);

        $this->app->singleton(ScoringEngine::class, function ($app) {
            return new ScoringEngine(
                $app->make(BMICalculator::class),
                $app->make(AgeClassifier::class),
            );
        });

        $this->app->singleton(RecommendationService::class, function ($app) {
            return new RecommendationService(
                $app->make(BMICalculator::class),
                $app->make(AgeClassifier::class),
                $app->make(HardFilter::class),
                $app->make(ScoringEngine::class),
            );
        });
    }

    public function boot(): void
    {
        $this->registerRateLimiters();
    }

    private function registerRateLimiters(): void
    {
        // Rate limit untuk generate rekomendasi
        // Max 10 request per menit per user
        RateLimiter::for('recommendation', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success'     => false,
                        'message'     => 'Terlalu banyak permintaan. Maksimal 10 rekomendasi per menit.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429);
                });
        });

        // Rate limit untuk auth (anti brute force)
        // Max 5 attempt per menit per IP
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success'     => false,
                        'message'     => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429);
                });
        });
    }
}
