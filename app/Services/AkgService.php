<?php

namespace App\Services;

use App\Models\Anak;

/**
 * AkgService
 * Menentukan kebutuhan energi harian anak.
 *
 * Referensi: AKG 2019 — Permenkes No. 28 Tahun 2019
 *
 * Catatan (validasi ahli gizi):
 * Nilai AKG Kemenkes digunakan LANGSUNG sebagai target kalori harian.
 * Angka AKG sudah merupakan kecukupan energi rata-rata anak Indonesia
 * pada kelompok usia & jenis kelamin tertentu, sehingga TIDAK dikalikan
 * lagi dengan faktor aktivitas (PAL).
 */
class AkgService
{
    /**
     * Tabel AKG kebutuhan energi harian (kkal/hari) per usia & jenis kelamin.
     * Format: [usia_min, usia_max, laki_kkal, perempuan_kkal]
     * Sumber: Lampiran PMK No. 28 Tahun 2019.
     */
    private const AKG_TABLE = [
        [1,  3,  1350, 1250],
        [4,  6,  1400, 1300],
        [7,  9,  1650, 1550],
        [10, 12, 2000, 1900],
        [13, 15, 2400, 2050],
        [16, 18, 2650, 2100],
    ];

    /**
     * Distribusi kalori per waktu makan untuk rencana HARIAN PENUH.
     * Hasil validasi ahli gizi (DM Tipe 1 anak):
     *   Sarapan      : 20%
     *   Snack Pagi   : 10%
     *   Makan Siang  : 25%
     *   Snack Sore   : 10%
     *   Makan Malam  : 25%
     *   Snack Malam  : 10%
     *
     * Prinsip: porsi kecil tapi sering -> gula darah lebih stabil.
     */
    private const DISTRIBUSI = [
        'sarapan'     => 0.20,
        'snack_pagi'  => 0.10,
        'makan_siang' => 0.25,
        'snack_sore'  => 0.10,
        'makan_malam' => 0.25,
        'snack_malam' => 0.10,
    ];

    /**
     * Ambil kebutuhan kalori harian anak langsung dari tabel AKG.
     * Tidak dikalikan PAL — angka AKG dipakai apa adanya.
     *
     * @throws \InvalidArgumentException jika usia di luar rentang AKG
     */
    public function hitungKebutuhanKalori(Anak $anak): float
    {
        return $this->getAkg($anak->usia, $anak->jenis_kelamin);
    }

    /**
     * Distribusi kalori ke 6 waktu makan harian.
     *
     * @param  float  $totalKalori  Kebutuhan kalori harian (AKG)
     * @return array<string, float>  ['sarapan' => x, 'snack_pagi' => y, ...]
     */
    public function distribusiKalori(float $totalKalori): array
    {
        $hasil = [];

        foreach (self::DISTRIBUSI as $waktu => $persen) {
            $hasil[$waktu] = round($totalKalori * $persen, 2);
        }

        return $hasil;
    }

    /**
     * Urutan waktu makan (untuk iterasi & tampilan).
     *
     * @return array<int, string>
     */
    public function urutanWaktuMakan(): array
    {
        return array_keys(self::DISTRIBUSI);
    }

    private function getAkg(int $usia, string $jenisKelamin): float
    {
        foreach (self::AKG_TABLE as [$min, $max, $laki, $perempuan]) {
            if ($usia >= $min && $usia <= $max) {
                return (float) ($jenisKelamin === 'L' ? $laki : $perempuan);
            }
        }

        throw new \InvalidArgumentException(
            "Usia {$usia} tahun di luar rentang tabel AKG (1-18 tahun)."
        );
    }
}
