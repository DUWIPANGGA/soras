<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $complaints = [
            [
                'name' => 'Nyeri Lutut',
                'slug' => 'nyeri_lutut',
            ],
            [
                'name' => 'Nyeri Sendi',
                'slug' => 'nyeri_sendi',
            ],
            [
                'name' => 'Nyeri Punggung',
                'slug' => 'nyeri_punggung',
            ],
            [
                'name' => 'Obesitas',
                'slug' => 'obesitas',
            ],
            [
                'name' => 'Hipertensi',
                'slug' => 'hipertensi',
            ],
            [
                'name' => 'Tekanan Darah Tinggi',
                'slug' => 'tekanan_darah_tinggi',
            ],
            [
                'name' => 'Stres',
                'slug' => 'stres',
            ],
            [
                'name' => 'Kurang Fleksibilitas',
                'slug' => 'kurang_fleksibilitas',
            ],
            [
                'name' => 'Lemah Otot',
                'slug' => 'lemah_otot',
            ],
            [
                'name' => 'Postur Buruk',
                'slug' => 'postur_buruk',
            ],
        ];

        foreach ($complaints as $complaint) {
            Complaint::create($complaint);
        }

        $this->command->info('✅ ComplaintSeeder selesai! Total: ' . count($complaints) . ' keluhan.');
    }
}
