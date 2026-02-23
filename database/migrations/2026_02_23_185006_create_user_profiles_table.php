<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->integer('age');
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->float('height_cm');
            $table->float('weight_kg');

            // Derived fields — dihitung oleh sistem
            $table->float('bmi')->nullable();
            $table->string('bmi_category')->nullable(); // Underweight/Normal/Overweight/Obesitas
            $table->string('age_category')->nullable(); // Anak/Remaja/Dewasa/Lansia

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
