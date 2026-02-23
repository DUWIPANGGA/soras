<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Master data dulu
            ExerciseSeeder::class,
            ComplaintSeeder::class,
            GoalSeeder::class,

            // Knowledge base (pivot) — harus setelah master data
            ComplaintExerciseSeeder::class,
            GoalExerciseSeeder::class,
        ]);
    }
}
