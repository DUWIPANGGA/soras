<?php

namespace App\Services\Recommendation;

use Illuminate\Support\Collection;

class HardFilter
{
    // Slug keluhan yang memicu aturan ketat
    const HYPERTENSION_SLUGS = [
        'hipertensi',
        'tekanan_darah_tinggi',
    ];

    const JOINT_INJURY_SLUGS = [
        'nyeri_lutut',
        'nyeri_sendi',
    ];

    // ─── Main Method ──────────────────────────────────────────────

    /**
     * Filter semua exercise yang tidak aman.
     * Return hanya exercise yang lolos semua rules.
     *
     * @param Collection $exercises      — semua exercise dari DB
     * @param string     $primarySlug    — slug keluhan utama
     * @param array      $secondarySlugs — slug keluhan tambahan
     * @param string     $bmiCategory    — hasil BMICalculator
     * @param string     $ageCategory    — hasil AgeClassifier
     */
    public function filter(
        Collection $exercises,
        string     $primarySlug,
        array      $secondarySlugs,
        string     $bmiCategory,
        string     $ageCategory,
    ): Collection {
        // Gabungkan semua slug keluhan jadi satu array
        $allComplaintSlugs = array_unique(
            array_merge([$primarySlug], $secondarySlugs)
        );

        return $exercises->filter(function ($exercise) use (
            $allComplaintSlugs,
            $primarySlug,
            $bmiCategory,
            $ageCategory,
        ) {
            // Jalankan semua rules — kalau ada yang triggered, eliminate
            if ($this->isHypertensionViolation($exercise, $allComplaintSlugs)) {
                return false;
            }

            if ($this->isJointInjuryViolation($exercise, $primarySlug)) {
                return false;
            }

            if ($this->isObesityViolation($exercise, $bmiCategory)) {
                return false;
            }

            if ($this->isGeriatricViolation($exercise, $ageCategory)) {
                return false;
            }

            // Lolos semua rules → aman
            return true;
        })->values(); // reset index collection
    }

    // ─── Rules ───────────────────────────────────────────────────

    /**
     * RULE 1 — Hipertensi tidak boleh olahraga High Intensity.
     *
     * Kenapa intensity bukan impact?
     * Karena hipertensi berbahaya saat detak jantung melonjak drastis,
     * bukan hanya karena benturan fisik.
     */
    private function isHypertensionViolation(
        $exercise,
        array $allComplaintSlugs
    ): bool {
        $hasHypertension = !empty(
            array_intersect($allComplaintSlugs, self::HYPERTENSION_SLUGS)
        );

        return $hasHypertension && $exercise->intensity_level === 3;
    }

    /**
     * RULE 2 — Nyeri Lutut / Sendi tidak boleh olahraga High Impact.
     *
     * Hanya berlaku untuk PRIMARY complaint karena kalau secondary,
     * kondisinya dianggap tidak terlalu dominan.
     */
    private function isJointInjuryViolation(
        $exercise,
        string $primarySlug
    ): bool {
        $hasJointInjury = in_array($primarySlug, self::JOINT_INJURY_SLUGS);

        return $hasJointInjury && $exercise->impact_level === 3;
    }

    /**
     * RULE 3 — Obesitas tidak boleh olahraga High Impact.
     *
     * Beban sendi terlalu besar saat impact tinggi dengan berat
     * badan berlebih, risiko cedera lutut dan pergelangan tinggi.
     */
    private function isObesityViolation(
        $exercise,
        string $bmiCategory
    ): bool {
        return $bmiCategory === 'Obesitas' && $exercise->impact_level === 3;
    }

    /**
     * RULE 4 — Lansia tidak boleh olahraga High Impact.
     *
     * Kepadatan tulang menurun, risiko fraktur meningkat
     * saat melakukan olahraga high impact.
     */
    private function isGeriatricViolation(
        $exercise,
        string $ageCategory
    ): bool {
        return $ageCategory === 'Lansia' && $exercise->impact_level === 3;
    }

    // ─── Helper Methods ───────────────────────────────────────────

    /**
     * Debugging — lihat exercise mana yang dieliminasi dan kenapa.
     * Berguna saat development & testing.
     */
    public function filterWithLog(
        Collection $exercises,
        string     $primarySlug,
        array      $secondarySlugs,
        string     $bmiCategory,
        string     $ageCategory,
    ): array {
        $allComplaintSlugs = array_unique(
            array_merge([$primarySlug], $secondarySlugs)
        );

        $passed     = [];
        $eliminated = [];

        foreach ($exercises as $exercise) {
            $reason = $this->getEliminationReason(
                $exercise,
                $allComplaintSlugs,
                $primarySlug,
                $bmiCategory,
                $ageCategory,
            );

            if ($reason) {
                $eliminated[] = [
                    'exercise' => $exercise->name,
                    'reason'   => $reason,
                ];
            } else {
                $passed[] = $exercise;
            }
        }

        return [
            'passed'     => collect($passed)->values(),
            'eliminated' => $eliminated,
        ];
    }

    /**
     * Tentukan alasan eliminasi untuk satu exercise.
     * Return null kalau exercise aman.
     */
    private function getEliminationReason(
        $exercise,
        array  $allComplaintSlugs,
        string $primarySlug,
        string $bmiCategory,
        string $ageCategory,
    ): ?string {
        if ($this->isHypertensionViolation($exercise, $allComplaintSlugs)) {
            return 'RULE 1: Hipertensi + High Intensity — risiko lonjakan tekanan darah';
        }

        if ($this->isJointInjuryViolation($exercise, $primarySlug)) {
            return 'RULE 2: Nyeri Sendi/Lutut + High Impact — risiko memperparah cedera';
        }

        if ($this->isObesityViolation($exercise, $bmiCategory)) {
            return 'RULE 3: Obesitas + High Impact — beban sendi terlalu besar';
        }

        if ($this->isGeriatricViolation($exercise, $ageCategory)) {
            return 'RULE 4: Lansia + High Impact — risiko fraktur tinggi';
        }

        return null;
    }
}