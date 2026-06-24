<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * FuzzyAhpService
 *
 * Implementasi Fuzzy AHP (Chang's Extent Analysis, 1996)
 * untuk meranking PAKET MENU HARIAN bagi anak DM Tipe 1.
 *
 * 4 Kriteria final (validasi ahli gizi, sumber data TKPI 2020):
 *   - TARGET  : kalori        -> deviasi total paket dari kebutuhan harian AKG
 *   - COST    : karbohidrat   -> total paket; lebih rendah lebih baik untuk DM
 *   - BENEFIT : protein       -> total paket; lebih tinggi lebih baik
 *   - BENEFIT : serat         -> total paket; lebih tinggi lebih baik
 *
 * Catatan: Indeks Glikemik TIDAK digunakan karena tidak tersedia di TKPI 2020.
 *
 * Bobot hasil Fuzzy AHP (terverifikasi, CR = 0.0038):
 *   Karbohidrat 42.44% > Kalori 24.74% = Serat 24.74% > Protein 8.09%
 */
class FuzzyAhpService
{
    // ── KONFIGURASI KRITERIA ────────────────────────────────────

    public const CRITERIA = ['kalori', 'karbohidrat', 'protein', 'serat'];

    private const CRITERIA_TYPE = [
        'kalori'      => 'target',   // deviasi dari kebutuhan harian
        'karbohidrat' => 'cost',
        'protein'     => 'benefit',
        'serat'       => 'benefit',
    ];

    /**
     * Skala TFN (Triangular Fuzzy Number) — Saaty dimodifikasi.
     * Format: [l, m, u]
     */
    private const TFN = [
        1 => [1, 1, 1],
        2 => [1, 2, 3],
        3 => [2, 3, 4],
        5 => [4, 5, 6],
        7 => [6, 7, 8],
        9 => [8, 9, 9],
    ];

    /**
     * Matriks perbandingan berpasangan — skala Saaty (crisp).
     * Urutan: [kalori, karbohidrat, protein, serat]
     *
     * Prioritas (DM Tipe 1 anak, validasi ahli gizi + literatur ADA/ISPAD):
     *   Karbohidrat > Kalori = Serat > Protein
     *
     * Nilai perbandingan:
     *   Karbohidrat vs Kalori   = 2 (sedikit lebih penting)
     *   Karbohidrat vs Protein  = 3 (cukup lebih penting)
     *   Karbohidrat vs Serat    = 2 (sedikit lebih penting)
     *   Kalori vs Serat         = 1 (setara)
     *   Kalori vs Protein       = 2 (sedikit lebih penting)
     *   Serat vs Protein        = 2 (sedikit lebih penting)
     */
    private array $pairwiseMatrix = [
        //  kalori  karbo   protein  serat
        [1,     1/2,    2,      1  ],  // kalori
        [2,     1,      3,      2  ],  // karbohidrat
        [1/2,   1/3,    1,      1/2],  // protein
        [1,     1/2,    2,      1  ],  // serat
    ];

    // Random Index Saaty untuk n=4
    private const RI = 0.90;

    // Cache bobot dalam satu request
    private ?array $cachedWeights = null;

    // ══════════════════════════════════════════════════════════
    // PUBLIC API
    // ══════════════════════════════════════════════════════════

    /**
     * Ranking paket menu harian.
     *
     * @param  array  $paketKandidat  Output dari PaketHarianService::generatePaketKandidat()
     *   Format tiap paket:
     *   [
     *     'id_paket'   => 'A',
     *     'menus'      => ['sarapan' => Menu, ...],
     *     'total_gizi' => ['kalori' => float, 'karbohidrat' => float, ...]
     *   ]
     * @param  float  $kebutuhanKalori  AKG harian anak
     * @return array  Paket lengkap + 'skor', 'ranking', 'bobot_kriteria', urut skor tertinggi
     */
    public function rankPaket(array $paketKandidat, float $kebutuhanKalori): array
    {
        if (empty($paketKandidat)) {
            return [];
        }

        $weights   = $this->hitungBobot();
        $rawValues = [];

        foreach ($paketKandidat as $paket) {
            $g = $paket['total_gizi'];
            $rawValues[] = [
                'paket'       => $paket,
                'kalori'      => abs($g['kalori'] - $kebutuhanKalori), // deviasi
                'karbohidrat' => $g['karbohidrat'],
                'protein'     => $g['protein'],
                'serat'       => $g['serat'],
            ];
        }

        $minMax  = $this->hitungMinMax($rawValues);
        $results = [];

        foreach ($rawValues as $raw) {
            $normalized = $this->normalisasi($raw, $minMax);
            $skor       = $this->hitungSkor($normalized, $weights);

            $results[] = [
                'paket'          => $raw['paket'],
                'skor'           => round($skor, 6),
                'bobot_kriteria' => $weights,
            ];
        }

        usort($results, fn($a, $b) => $b['skor'] <=> $a['skor']);

        foreach ($results as $i => &$r) {
            $r['ranking'] = $i + 1;
        }

        return $results;
    }

    /** Bobot kriteria hasil Fuzzy AHP */
    public function getBobot(): array
    {
        return $this->hitungBobot();
    }

    /**
     * Consistency Ratio — disimpan untuk audit admin, tidak ditampilkan ke parent.
     * Nilai valid: CR < 0.10
     */
    public function getConsistencyRatio(): float
    {
        return $this->hitungConsistencyRatio();
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 1 — FUZZY PAIRWISE MATRIX
    // ══════════════════════════════════════════════════════════

    private function buildFuzzyMatrix(): array
    {
        $n     = count($this->pairwiseMatrix);
        $fuzzy = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $val = $this->pairwiseMatrix[$i][$j];

                if ($val >= 1) {
                    $fuzzy[$i][$j] = $this->getTfn($val);
                } else {
                    [$l, $m, $u]   = $this->getTfn(1 / $val);
                    $fuzzy[$i][$j] = [1 / $u, 1 / $m, 1 / $l];
                }
            }
        }

        return $fuzzy;
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 2 — SYNTHETIC EXTENT (Si)
    // ══════════════════════════════════════════════════════════

    private function hitungSyntheticExtent(array $fuzzyMatrix): array
    {
        $n = count($fuzzyMatrix);
        $totalL = $totalM = $totalU = 0.0;

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $totalL += $fuzzyMatrix[$i][$j][0];
                $totalM += $fuzzyMatrix[$i][$j][1];
                $totalU += $fuzzyMatrix[$i][$j][2];
            }
        }

        $Si = [];
        for ($i = 0; $i < $n; $i++) {
            $rowL = array_sum(array_column($fuzzyMatrix[$i], 0));
            $rowM = array_sum(array_column($fuzzyMatrix[$i], 1));
            $rowU = array_sum(array_column($fuzzyMatrix[$i], 2));

            $Si[] = [
                $rowL / $totalU,
                $rowM / $totalM,
                $rowU / $totalL,
            ];
        }

        return $Si;
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 3 — DEGREE OF POSSIBILITY V(Si >= Sj)
    // ══════════════════════════════════════════════════════════

    private function degreeOfPossibility(array $Si, array $Sj): float
    {
        [$l1, $m1, $u1] = $Si;
        [$l2, $m2, $u2] = $Sj;

        if ($m1 >= $m2) return 1.0;
        if ($l2 >= $u1) return 0.0;

        $denom = ($m1 - $u1) - ($m2 - $l2);

        return abs($denom) < 1e-10 ? 0.0 : ($l2 - $u1) / $denom;
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 4 — BOBOT KRITERIA
    // ══════════════════════════════════════════════════════════

    private function hitungBobot(): array
    {
        if ($this->cachedWeights !== null) {
            return $this->cachedWeights;
        }

        $fuzzyMatrix = $this->buildFuzzyMatrix();
        $Si          = $this->hitungSyntheticExtent($fuzzyMatrix);
        $n           = count($Si);
        $dPrime      = [];

        for ($i = 0; $i < $n; $i++) {
            $min = PHP_FLOAT_MAX;
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) continue;
                $min = min($min, $this->degreeOfPossibility($Si[$i], $Si[$j]));
            }
            $dPrime[$i] = max($min, 0.0);
        }

        $total   = array_sum($dPrime);
        $weights = [];

        foreach (self::CRITERIA as $idx => $key) {
            $weights[$key] = $total > 0
                ? round($dPrime[$idx] / $total, 6)
                : 0.0;
        }

        $this->cachedWeights = $weights;

        return $weights;
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 5 — NORMALISASI MIN-MAX
    // ══════════════════════════════════════════════════════════

    private function normalisasi(array $raw, array $minMax): array
    {
        $normal = [];

        foreach (self::CRITERIA as $key) {
            $min   = $minMax[$key]['min'];
            $max   = $minMax[$key]['max'];
            $range = $max - $min;
            $nilai = $raw[$key];

            if ($range == 0) {
                $normal[$key] = 1.0;
                continue;
            }

            $type = self::CRITERIA_TYPE[$key];

            $normal[$key] = match ($type) {
                'benefit' => round(($nilai - $min) / $range, 6),
                'cost'    => round(($max - $nilai) / $range, 6),
                'target'  => round(($max - $nilai) / $range, 6),
                default   => 0.0,
            };
        }

        return $normal;
    }

    // ══════════════════════════════════════════════════════════
    // LANGKAH 6 — SKOR AKHIR
    // ══════════════════════════════════════════════════════════

    private function hitungSkor(array $normalized, array $weights): float
    {
        $skor = 0.0;

        foreach (self::CRITERIA as $key) {
            $skor += ($weights[$key] ?? 0) * ($normalized[$key] ?? 0);
        }

        return $skor;
    }

    // ══════════════════════════════════════════════════════════
    // HELPER — MIN-MAX PER KRITERIA
    // ══════════════════════════════════════════════════════════

    private function hitungMinMax(array $rawValues): array
    {
        $minMax = [];

        foreach (self::CRITERIA as $key) {
            $minMax[$key] = ['min' => PHP_FLOAT_MAX, 'max' => -PHP_FLOAT_MAX];
        }

        foreach ($rawValues as $row) {
            foreach (self::CRITERIA as $key) {
                $minMax[$key]['min'] = min($minMax[$key]['min'], $row[$key]);
                $minMax[$key]['max'] = max($minMax[$key]['max'], $row[$key]);
            }
        }

        return $minMax;
    }

    // ══════════════════════════════════════════════════════════
    // HELPER — CONSISTENCY RATIO
    // ══════════════════════════════════════════════════════════

    public function hitungConsistencyRatio(): float
    {
        $n      = count($this->pairwiseMatrix);
        $matrix = $this->pairwiseMatrix;

        $colSum = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $colSum[$j] += $matrix[$i][$j];
            }
        }

        $w = [];
        for ($i = 0; $i < $n; $i++) {
            $rowSum = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $rowSum += $matrix[$i][$j] / $colSum[$j];
            }
            $w[$i] = $rowSum / $n;
        }

        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $ws = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $ws += $matrix[$i][$j] * $w[$j];
            }
            $lambdaMax += $ws / $w[$i];
        }
        $lambdaMax /= $n;

        $ci = ($lambdaMax - $n) / ($n - 1);
        $cr = $ci / self::RI;

        return round(abs($cr), 4);
    }

    // ══════════════════════════════════════════════════════════
    // HELPER — TFN LOOKUP
    // ══════════════════════════════════════════════════════════

    private function getTfn(float $value): array
    {
        $closest = 1;
        $minDiff = PHP_FLOAT_MAX;

        foreach (array_keys(self::TFN) as $scale) {
            $diff = abs(round($value) - $scale);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $scale;
            }
        }

        return self::TFN[$closest];
    }
}
