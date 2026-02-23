<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            [
                'name'               => 'Jalan Kaki',
                'category'           => 'Kardio',
                'impact_level'       => 1,
                'intensity_level'    => 1,
                'duration_min'       => 30,
                'frequency_per_week' => 5,
                'description'        => 'Aktivitas kardio ringan dengan risiko cedera rendah.',
            ],
            [
                'name'               => 'Lari',
                'category'           => 'Kardio',
                'impact_level'       => 3,
                'intensity_level'    => 3,
                'duration_min'       => 25,
                'frequency_per_week' => 4,
                'description'        => 'Kardio intensitas sedang hingga tinggi.',
            ],
            [
                'name'               => 'Bersepeda',
                'category'           => 'Kardio',
                'impact_level'       => 2,
                'intensity_level'    => 2,
                'duration_min'       => 30,
                'frequency_per_week' => 4,
                'description'        => 'Kardio dengan tekanan sendi lebih rendah dibanding lari.',
            ],
            [
                'name'               => 'Berenang',
                'category'           => 'Kardio',
                'impact_level'       => 1,
                'intensity_level'    => 2,
                'duration_min'       => 30,
                'frequency_per_week' => 4,
                'description'        => 'Latihan seluruh tubuh dengan tekanan minimal pada sendi.',
            ],
            [
                'name'               => 'Yoga',
                'category'           => 'Fleksibilitas',
                'impact_level'       => 1,
                'intensity_level'    => 1,
                'duration_min'       => 40,
                'frequency_per_week' => 4,
                'description'        => 'Latihan fleksibilitas dan relaksasi.',
            ],
            [
                'name'               => 'Latihan Kekuatan',
                'category'           => 'Kekuatan',
                'impact_level'       => 2,
                'intensity_level'    => 2,
                'duration_min'       => 25,
                'frequency_per_week' => 3,
                'description'        => 'Latihan menggunakan berat badan atau beban ringan.',
            ],
            [
                'name'               => 'HIIT',
                'category'           => 'Kardio Intensitas Tinggi',
                'impact_level'       => 3,
                'intensity_level'    => 3,
                'duration_min'       => 20,
                'frequency_per_week' => 3,
                'description'        => 'Latihan interval intensitas tinggi.',
            ],
            [
                'name'               => 'Peregangan',
                'category'           => 'Fleksibilitas',
                'impact_level'       => 1,
                'intensity_level'    => 1,
                'duration_min'       => 20,
                'frequency_per_week' => 5,
                'description'        => 'Latihan ringan untuk meningkatkan mobilitas.',
            ],
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }

        $this->command->info('✅ ExerciseSeeder selesai! Total: ' . count($exercises) . ' olahraga.');
    }
}
