<?php

namespace App\Services\Recommendation;

class BMICalculator
{
    // ─── Konstanta Kategori ───────────────────────────────────────

    const CATEGORY_UNDERWEIGHT = 'Underweight';
    const CATEGORY_NORMAL      = 'Normal';
    const CATEGORY_OVERWEIGHT  = 'Overweight';
    const CATEGORY_OBESITAS    = 'Obesitas';

    // ─── Matriks Skor BMI vs Olahraga ────────────────────────────
    // Sumber: knowledge base yang sudah kita definisikan
    // Format: category => [exercise_name => score]

    const SCORE_MATRIX = [
        self::CATEGORY_UNDERWEIGHT => [
            'Jalan Kaki'       => 0.7,
            'Lari'             => 0.6,
            'Bersepeda'        => 0.7,
            'Berenang'         => 0.8,
            'Yoga'             => 0.9,
            'Latihan Kekuatan' => 1.0,
            'HIIT'             => 0.6,
            'Peregangan'       => 0.8,
        ],
        self::CATEGORY_NORMAL => [
            'Jalan Kaki'       => 0.8,
            'Lari'             => 1.0,
            'Bersepeda'        => 0.9,
            'Berenang'         => 0.9,
            'Yoga'             => 0.8,
            'Latihan Kekuatan' => 0.9,
            'HIIT'             => 0.9,
            'Peregangan'       => 0.7,
        ],
        self::CATEGORY_OVERWEIGHT => [
            'Jalan Kaki'       => 1.0,
            'Lari'             => 0.5,
            'Bersepeda'        => 0.9,
            'Berenang'         => 1.0,
            'Yoga'             => 0.8,
            'Latihan Kekuatan' => 0.8,
            'HIIT'             => 0.3,
            'Peregangan'       => 0.7,
        ],
        self::CATEGORY_OBESITAS => [
            'Jalan Kaki'       => 1.0,
            'Lari'             => 0.2,
            'Bersepeda'        => 0.9,
            'Berenang'         => 1.0,
            'Yoga'             => 0.9,
            'Latihan Kekuatan' => 0.7,
            'HIIT'             => 0.0,
            'Peregangan'       => 0.8,
        ],
    ];

    // ─── Core Methods ─────────────────────────────────────────────

    /**
     * Hitung nilai BMI dari tinggi dan berat.
     */
    public function calculate(float $heightCm, float $weightKg): float
    {
        $heightM = $heightCm / 100;

        return round($weightKg / ($heightM * $heightM), 2);
    }

    /**
     * Kategorikan nilai BMI berdasarkan standar WHO.
     */
    public function categorize(float $bmi): string
    {
        return match(true) {
            $bmi < 18.5 => self::CATEGORY_UNDERWEIGHT,
            $bmi < 25.0 => self::CATEGORY_NORMAL,
            $bmi < 30.0 => self::CATEGORY_OVERWEIGHT,
            default     => self::CATEGORY_OBESITAS,
        };
    }

    /**
     * Ambil skor relevansi BMI untuk olahraga tertentu.
     * Dipanggil oleh ScoringEngine saat menghitung score_bmi.
     */
    public function getScore(string $category, string $exerciseName): float
    {
        return self::SCORE_MATRIX[$category][$exerciseName] ?? 0.0;
    }

    /**
     * Hitung + kategorikan sekaligus.
     * Return array lengkap siap pakai.
     */
    public function process(float $heightCm, float $weightKg): array
    {
        $bmi      = $this->calculate($heightCm, $weightKg);
        $category = $this->categorize($bmi);

        return [
            'bmi'      => $bmi,
            'category' => $category,
        ];
    }
}