<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goal_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('exercise_id')
                ->constrained()
                ->onDelete('cascade');

            // Nilai relevansi: 0.0 s/d 1.0
            $table->float('relevance_score');

            $table->timestamps();

            // Satu pasang goal-exercise hanya boleh ada sekali
            $table->unique(['goal_id', 'exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_exercise');
    }
};
