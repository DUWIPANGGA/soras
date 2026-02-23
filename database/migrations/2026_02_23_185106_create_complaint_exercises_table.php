<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaint_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('exercise_id')
                ->constrained()
                ->onDelete('cascade');

            // Nilai relevansi: -0.5 s/d 1.0
            $table->float('relevance_score');

            $table->timestamps();

            // Satu pasang complaint-exercise hanya boleh ada sekali
            $table->unique(['complaint_id', 'exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_exercise');
    }
};
