<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommendation_score_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('exercise_id')
                ->constrained()
                ->onDelete('cascade');

            // Breakdown tiap faktor — untuk transparansi akademik
            $table->float('score_primary')->nullable();
            $table->float('score_secondary')->nullable();
            $table->float('score_goal')->nullable();
            $table->float('score_bmi')->nullable();
            $table->float('score_age')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_score_breakdowns');
    }
};
