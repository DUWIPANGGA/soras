<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')
                ->constrained()
                ->onDelete('cascade');

            // Snapshot konteks saat rekomendasi dibuat
            $table->foreignId('primary_complaint_id')
                ->nullable()
                ->constrained('complaints')
                ->nullOnDelete();

            $table->foreignId('goal_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Skor & confidence rekomendasi teratas
            $table->float('final_score')->nullable();
            $table->float('confidence')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
