<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;
use App\Models\Exercise;

class ComplaintExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // Matriks: complaint_slug => [exercise_name => relevance_score]
        $matrix = [
            'nyeri_lutut' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => -0.5,
                'Bersepeda'         => 1.0,
                'Berenang'          => 1.0,
                'Yoga'              => 0.7,
                'Latihan Kekuatan'  => 0.3,
                'HIIT'              => -0.5,
                'Peregangan'        => 0.7,
            ],
            'nyeri_sendi' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => -0.5,
                'Bersepeda'         => 0.8,
                'Berenang'          => 1.0,
                'Yoga'              => 0.8,
                'Latihan Kekuatan'  => 0.3,
                'HIIT'              => -0.5,
                'Peregangan'        => 0.8,
            ],
            'nyeri_punggung' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.5,
                'Berenang'          => 0.8,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.7,
                'HIIT'              => 0.0,
                'Peregangan'        => 1.0,
            ],
            'obesitas' => [
                'Jalan Kaki'        => 1.0,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.8,
                'Berenang'          => 1.0,
                'Yoga'              => 0.7,
                'Latihan Kekuatan'  => 0.7,
                'HIIT'              => 0.0,
                'Peregangan'        => 0.5,
            ],
            'hipertensi' => [
                'Jalan Kaki'        => 1.0,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.8,
                'Berenang'          => 0.9,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.5,
                'HIIT'              => -0.5,
                'Peregangan'        => 0.8,
            ],
            'tekanan_darah_tinggi' => [
                'Jalan Kaki'        => 1.0,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.8,
                'Berenang'          => 0.9,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.5,
                'HIIT'              => -0.5,
                'Peregangan'        => 0.8,
            ],
            'stres' => [
                'Jalan Kaki'        => 0.7,
                'Lari'              => 0.7,
                'Bersepeda'         => 0.7,
                'Berenang'          => 0.8,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.6,
                'Peregangan'        => 0.9,
            ],
            'kurang_fleksibilitas' => [
                'Jalan Kaki'        => 0.5,
                'Lari'              => 0.3,
                'Bersepeda'         => 0.4,
                'Berenang'          => 0.6,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.6,
                'HIIT'              => 0.2,
                'Peregangan'        => 1.0,
            ],
            'lemah_otot' => [
                'Jalan Kaki'        => 0.6,
                'Lari'              => 0.7,
                'Bersepeda'         => 0.7,
                'Berenang'          => 0.8,
                'Yoga'              => 0.7,
                'Latihan Kekuatan'  => 1.0,
                'HIIT'              => 0.8,
                'Peregangan'        => 0.5,
            ],
            'postur_buruk' => [
                'Jalan Kaki'        => 0.6,
                'Lari'              => 0.4,
                'Bersepeda'         => 0.5,
                'Berenang'          => 0.8,
                'Yoga'              => 1.0,
                'Latihan Kekuatan'  => 0.9,
                'HIIT'              => 0.3,
                'Peregangan'        => 0.9,
            ],
        ];

        // Cache semua exercise supaya tidak query berkali-kali
        $exercises = Exercise::all()->keyBy('name');

        foreach ($matrix as $complaintSlug => $exerciseScores) {
            $complaint = Complaint::where('slug', $complaintSlug)->first();

            if (!$complaint) {
                $this->command->warn("Complaint tidak ditemukan: {$complaintSlug}");
                continue;
            }

            foreach ($exerciseScores as $exerciseName => $score) {
                $exercise = $exercises->get($exerciseName);

                if (!$exercise) {
                    $this->command->warn("Exercise tidak ditemukan: {$exerciseName}");
                    continue;
                }

                // updateOrCreate supaya aman dijalankan berkali-kali
                $complaint->exercises()->syncWithoutDetaching([
                    $exercise->id => ['relevance_score' => $score]
                ]);
            }
        }

        $this->command->info('✅ ComplaintExerciseSeeder selesai!');
    }
}
