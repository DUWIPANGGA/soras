<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            [
                'name'        => 'Menurunkan Berat Badan',
                'slug'        => 'weight_loss',
                'description' => 'Membakar kalori dan mengurangi lemak tubuh.',
            ],
            [
                'name'        => 'Meningkatkan Daya Tahan',
                'slug'        => 'endurance',
                'description' => 'Meningkatkan stamina dan kapasitas aerobik.',
            ],
            [
                'name'        => 'Meningkatkan Kekuatan Otot',
                'slug'        => 'muscle_strength',
                'description' => 'Membangun massa dan kekuatan otot.',
            ],
            [
                'name'        => 'Mengurangi Nyeri / Rehabilitasi',
                'slug'        => 'rehabilitation',
                'description' => 'Memulihkan kondisi fisik dan mengurangi nyeri.',
            ],
            [
                'name'        => 'Mengurangi Stres',
                'slug'        => 'stress_relief',
                'description' => 'Menurunkan tingkat stres dan kecemasan.',
            ],
            [
                'name'        => 'Meningkatkan Fleksibilitas',
                'slug'        => 'flexibility',
                'description' => 'Meningkatkan kelenturan dan mobilitas tubuh.',
            ],
            [
                'name'        => 'Meningkatkan Kesehatan Jantung',
                'slug'        => 'heart_health',
                'description' => 'Menjaga dan meningkatkan kesehatan kardiovaskular.',
            ],
        ];

        foreach ($goals as $goal) {
            Goal::create($goal);
        }

        $this->command->info('✅ GoalSeeder selesai! Total: ' . count($goals) . ' tujuan latihan.');
    }
}
