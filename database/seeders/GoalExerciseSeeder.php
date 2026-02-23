<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;
use App\Models\Exercise;

class GoalExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // Matriks: goal_slug => [exercise_name => relevance_score]
        $matrix = [
            'weight_loss' => [
                'Jalan Kaki'        => 0.8,
                'Lari'              => 1.0,
                'Bersepeda'         => 0.9,
                'Berenang'          => 0.9,
                'Yoga'              => 0.5,
                'Latihan Kekuatan'  => 0.7,
                'HIIT'              => 1.0,
                'Peregangan'        => 0.2,
            ],
            'endurance' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => 1.0,
                'Bersepeda'         => 0.9,
                'Berenang'          => 0.9,
                'Yoga'              => 0.5,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.9,
                'Peregangan'        => 0.2,
            ],
            'muscle_strength' => [
                'Jalan Kaki'        => 0.5,
                'Lari'              => 0.6,
                'Bersepeda'         => 0.6,
                'Berenang'          => 0.8,
                'Yoga'              => 0.7,
                'Latihan Kekuatan'  => 1.0,
                'HIIT'              => 0.9,
                'Peregangan'        => 0.4,
            ],
            'rehabilitation' => [
                'Jalan Kaki'        => 0.8,
                'Lari'              => 0.2,
                'Bersepeda'         => 0.9,
                'Berenang'          => 1.0,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.0,
                'Peregangan'        => 1.0,
            ],
            'stress_relief' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => 0.7,
                'Bersepeda'         => 0.7,
                'Berenang'          => 0.8,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.6,
                'Peregangan'        => 0.9,
            ],
            'flexibility' => [
                'Jalan Kaki'        => 0.5,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.4,
                'Berenang'          => 0.6,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.2,
                'Peregangan'        => 1.0,
            ],
            'heart_health' => [
                'Jalan Kaki'        => 0.9,
                'Lari'              => 1.0,
                'Bersepeda'         => 0.9,
                'Berenang'          => 0.9,
                'Yoga'              => 0.6,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.9,
                'Peregangan'        => 0.3,
            ],
        ];

        // Cache semua exercise supaya tidak query berkali-kali
        $exercises = Exercise::all()->keyBy('name');

        foreach ($matrix as $goalSlug => $exerciseScores) {
            $goal = Goal::where('slug', $goalSlug)->first();

            if (!$goal) {
                $this->command->warn("Goal tidak ditemukan: {$goalSlug}");
                continue;
            }

            foreach ($exerciseScores as $exerciseName => $score) {
                $exercise = $exercises->get($exerciseName);

                if (!$exercise) {
                    $this->command->warn("Exercise tidak ditemukan: {$exerciseName}");
                    continue;
                }

                $goal->exercises()->syncWithoutDetaching([
                    $exercise->id => ['relevance_score' => $score]
                ]);
            }
        }

        $this->command->info('✅ GoalExerciseSeeder selesai!');
    }
}
