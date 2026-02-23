<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->tinyInteger('impact_level');    // 1=low, 2=medium, 3=high
            $table->tinyInteger('intensity_level'); // 1=low, 2=medium, 3=high
            $table->integer('duration_min');
            $table->integer('frequency_per_week');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
