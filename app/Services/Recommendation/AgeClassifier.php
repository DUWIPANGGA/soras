<?php

namespace App\Services\Recommendation;

class AgeClassifier
{
    // ─── Konstanta Kategori Usia ──────────────────────────────────

    const CATEGORY_ANAK   = 'Anak';
    const CATEGORY_REMAJA = 'Remaja';
    const CATEGORY_DEWASA = 'Dewasa';
    const CATEGORY_LANSIA = 'Lansia';

    // ─── Konstanta Impact Level ───────────────────────────────────

    const IMPACT_LOW    = 1;
    const IMPACT_MEDIUM = 2;
    const IMPACT_HIGH   = 3;

    // ─── Matriks Skor Usia vs Impact Level ───────────────────────
    // Format: age_category => [impact_level => score]

    const SCORE_MATRIX = [
        self::CATEGORY_ANAK => [
            self::IMPACT_LOW    => 1.0,
            self::IMPACT_MEDIUM => 0.7,
            self::IMPACT_HIGH   => 0.3,
        ],
        self::CATEGORY_REMAJA => [
            self::IMPACT_LOW    => 1.0,
            self::IMPACT_MEDIUM => 0.9,
            self::IMPACT_HIGH   => 0.7,
        ],
        self::CATEGORY_DEWASA => [
            self::IMPACT_LOW    => 1.0,
            self::IMPACT_MEDIUM => 1.0,
            self::IMPACT_HIGH   => 0.9,
        ],
        self::CATEGORY_LANSIA => [
            self::IMPACT_LOW    => 1.0,
            self::IMPACT_MEDIUM => 0.6,
            self::IMPACT_HIGH   => 0.0,
        ],
    ];

    // ─── Core Methods ─────────────────────────────────────────────

    /**
     * Kategorikan usia menjadi kelompok umur.
     */
    public function categorize(int $age): string
    {
        return match(true) {
            $age < 13  => self::CATEGORY_ANAK,
            $age < 18  => self::CATEGORY_REMAJA,
            $age < 50  => self::CATEGORY_DEWASA,
            default    => self::CATEGORY_LANSIA,
        };
    }

    /**
     * Ambil skor AgeImpact untuk olahraga tertentu.
     * Dipanggil oleh ScoringEngine saat menghitung score_age.
     *
     * @param string $category   — hasil categorize()
     * @param int    $impactLevel — impact_level dari tabel exercises
     */
    public function getScore(string $category, int $impactLevel): float
    {
        return self::SCORE_MATRIX[$category][$impactLevel] ?? 0.0;
    }

    /**
     * Kategorikan sekaligus ambil skor.
     * Return array lengkap siap pakai.
     *
     * @param int $age
     * @param int $impactLevel — impact_level dari exercise
     */
    public function process(int $age, int $impactLevel): array
    {
        $category = $this->categorize($age);
        $score    = $this->getScore($category, $impactLevel);

        return [
            'category' => $category,
            'score'    => $score,
        ];
    }

    // ─── Helper Methods ───────────────────────────────────────────

    /**
     * Apakah kategori ini termasuk lansia?
     * Dipakai oleh HardFilter nanti.
     */
    public function isLansia(string $category): bool
    {
        return $category === self::CATEGORY_LANSIA;
    }

    /**
     * Apakah kategori ini termasuk anak-anak?
     * Dipakai oleh HardFilter nanti.
     */
    public function isAnak(string $category): bool
    {
        return $category === self::CATEGORY_ANAK;
    }
}