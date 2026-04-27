<?php

namespace App\Services;

use App\Models\Anak;

/**
 * AkgService
 * Menghitung kebutuhan energi harian anak.
 *
 * Referensi: AKG 2019 — Permenkes No. 28 Tahun 2019
 * Faktor PAL: WHO/FAO/UNU 2004
 */
class AkgService
{
    /**
     * Tabel AKG basal (kkal/hari) per usia & jenis kelamin.
     * Format: [usia_min, usia_max, laki_kkal, perempuan_kkal]
     */
    private const AKG_TABLE = [
        [1,  3,  1350, 1250],
        [4,  6,  1400, 1300],
        [7,  9,  1650, 1550],
        [10, 12, 2000, 1900],
        [13, 15, 2400, 2050],
        [16, 18, 2650, 2100],
    ];

    private const PAL = [
        'ringan' => 1.40,
        'sedang' => 1.60,
        'berat'  => 1.75,
    ];

    /**
     * Hitung Total Energy Expenditure (TEE) anak.
     * TEE = AKG Basal × PAL
     *
     * @throws \InvalidArgumentException
     */
    public function hitungKebutuhanKalori(Anak $anak): float
    {
        $akgBasal = $this->getAkgBasal($anak->usia, $anak->jenis_kelamin);
        $pal      = self::PAL[$anak->tingkat_aktivitas] ?? self::PAL['sedang'];

        return round($akgBasal * $pal, 2);
    }

    /**
     * Distribusi kalori per waktu makan.
     * Referensi: Konsensus DM Tipe 1 Anak — IDAI 2015
     *   Sarapan    : 25%
     *   Makan Siang: 35%
     *   Makan Malam: 25%
     *
     * @return array<string, float>
     */
    public function distribusiKalori(float $totalKalori): array
    {
        return [
            'sarapan'     => round($totalKalori * 0.25, 2),
            'makan_siang' => round($totalKalori * 0.35, 2),
            'makan_malam' => round($totalKalori * 0.25, 2),
        ];
    }

    private function getAkgBasal(int $usia, string $jenisKelamin): float
    {
        foreach (self::AKG_TABLE as [$min, $max, $laki, $perempuan]) {
            if ($usia >= $min && $usia <= $max) {
                return $jenisKelamin === 'L' ? $laki : $perempuan;
            }
        }

        throw new \InvalidArgumentException(
            "Usia {$usia} tahun di luar rentang tabel AKG (1–18 tahun)."
        );
    }
}
