<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * FuzzyAhpService
 *
 * Implementasi Fuzzy AHP (Chang's Extent Analysis, 1996)
 * untuk rekomendasi menu harian anak Diabetes Mellitus Tipe 1.
 *
 * Tipe Kriteria:
 * - TARGET  : kalori          → deviasi dari kebutuhan kalori (lebih kecil = lebih baik)
 * - COST    : karbohidrat     → nilai lebih rendah = lebih baik untuk DM
 * - BENEFIT : protein         → nilai lebih tinggi = lebih baik
 * - BENEFIT : serat           → nilai lebih tinggi = lebih baik
 * - COST    : indeks_glikemik → nilai lebih rendah = lebih baik untuk DM
 */
class FuzzyAhpService
{
    // ── KONFIGURASI KRITERIA ──────────────────────────────────────

    private const CRITERIA = ['kalori', 'karbohidrat', 'protein', 'serat', 'indeks_glikemik'];

    private const CRITERIA_TYPE = [
        'kalori'          => 'target',   // deviasi dari kebutuhan
        'karbohidrat'     => 'cost',
        'protein'         => 'benefit',
        'serat'           => 'benefit',
        'indeks_glikemik' => 'cost',
    ];

    /**
     * Skala TFN (Triangular Fuzzy Number) — Saaty dimodifikasi untuk fuzzy.
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
     * Urutan: [kalori, karbohidrat, protein, serat, indeks_glikemik]
     *
     * Justifikasi prioritas untuk DM Tipe 1 anak:
     * - Indeks Glikemik → paling kritis (langsung pengaruhi gula darah)
     * - Karbohidrat     → sangat penting (sumber utama glukosa)
     * - Serat           → penting (memperlambat absorpsi glukosa)
     * - Protein         → penting untuk pertumbuhan anak
     * - Kalori          → disesuaikan dengan kebutuhan energi harian
     */
    private array $pairwiseMatrix = [
        //  kalori  karbo   protein  serat   IG
        [1,     1/3,    1/2,    1/3,    1/5],  // kalori
        [3,     1,      2,      1,      1/3],  // karbohidrat
        [2,     1/2,    1,      1/2,    1/3],  // protein
        [3,     1,      2,      1,      1/2],  // serat
        [5,     3,      3,      2,      1  ],  // indeks_glikemik
    ];

    // Cache bobot agar tidak dihitung berulang dalam satu request
    private ?array $cachedWeights = null;

    // ── PUBLIC API ────────────────────────────────────────────────

    /**
     * Hitung ranking menu berdasarkan Fuzzy AHP.
     *
     * @param  Collection  $menus            Menu::with('kandunganGizi')
     * @param  float       $kebutuhanKalori  Kalori untuk 1 waktu makan (dari AkgService)
     * @return array  Menu yang sudah diranking, skor tertinggi = ranking 1
     */
    public function rankMenus(Collection $menus, float $kebutuhanKalori): array
    {
        $weights   = $this->hitungBobot();
        $rawValues = $this->collectRawValues($menus, $kebutuhanKalori);

        if (empty($rawValues)) {
            return [];
        }

        $minMax  = $this->hitungMinMax($rawValues);
        $results = [];

        foreach ($rawValues as $raw) {
            $normalized = $this->normalisasi($raw, $minMax);
            $skor       = $this->hitungSkor($normalized, $weights);

            $results[] = [
                'menu_id'        => $raw['menu_id'],
                'nama_menu'      => $raw['nama_menu'],
                'jenis_menu'     => $raw['jenis_menu'],
                'skor'           => round($skor, 6),
                'bobot_kriteria' => $weights,
            ];
        }

        // Urutkan: skor tertinggi = ranking 1
        usort($results, fn($a, $b) => $b['skor'] <=> $a['skor']);

        foreach ($results as $i => &$result) {
            $result['ranking'] = $i + 1;
        }

        return $results;
    }

    /** Kembalikan bobot kriteria hasil Fuzzy AHP */
    public function getBobot(): array
    {
        return $this->hitungBobot();
    }

    /**
     * Kembalikan CR untuk validasi di Controller.
     * CR < 0.1 = matriks konsisten (syarat akademis AHP).
     * Tidak disimpan ke database.
     */
    public function getConsistencyRatio(): float
    {
        return $this->hitungConsistencyRatio();
    }

    // ── STEP 1: FUZZY PAIRWISE MATRIX ────────────────────────────

    /**
     * Konversi matriks crisp ke TFN.
     * Nilai >= 1 → ambil TFN dari tabel.
     * Nilai <  1 → balik TFN: (1/u, 1/m, 1/l).
     */
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

    // ── STEP 2: FUZZY SYNTHETIC EXTENT (Si) ──────────────────────

    /**
     * Si = (Σj Mij) ⊗ (1 / Σi Σj Mij)
     */
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

    // ── STEP 3: DEGREE OF POSSIBILITY ────────────────────────────

    /**
     * V(Si >= Sj) — Chang (1996):
     *   1                                jika m1 >= m2
     *   0                                jika l2 >= u1
     *   (l2 - u1) / (m1 - u1 - m2 + l2) selainnya
     */
    private function degreeOfPossibility(array $Si, array $Sj): float
    {
        [$l1, $m1, $u1] = $Si;
        [$l2, $m2, $u2] = $Sj;

        if ($m1 >= $m2) return 1.0;
        if ($l2 >= $u1) return 0.0;

        $denom = ($m1 - $u1) - ($m2 - $l2);

        return abs($denom) < 1e-10 ? 0.0 : ($l2 - $u1) / $denom;
    }

    // ── STEP 4: BOBOT KRITERIA ────────────────────────────────────

    /**
     * d'i = min V(Si >= Sj), j ≠ i
     * Wi  = d'i / Σ d'i
     */
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

    // ── STEP 5: NORMALISASI MIN-MAX ───────────────────────────────

    /**
     * benefit → (nilai - min) / (max - min)
     * cost    → (max - nilai) / (max - min)
     * target  → deviasi sudah dihitung di collectRawValues → perlakukan sebagai cost
     */
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

            $normal[$key] = match($type) {
                'benefit' => round(($nilai - $min) / $range, 6),
                'cost'    => round(($max - $nilai) / $range, 6),
                'target'  => round(($max - $nilai) / $range, 6), // deviasi → as cost
                default   => 0.0,
            };
        }

        return $normal;
    }

    // ── STEP 6: SKOR AKHIR ───────────────────────────────────────

    /** Skor = Σ (Wi × Nilai_Normal_i) */
    private function hitungSkor(array $normalized, array $weights): float
    {
        $skor = 0.0;

        foreach (self::CRITERIA as $key) {
            $skor += ($weights[$key] ?? 0) * ($normalized[$key] ?? 0);
        }

        return $skor;
    }

    // ── HELPER: KUMPULKAN NILAI MENTAH ───────────────────────────

    /**
     * Untuk kriteria 'kalori':
     * → Hitung deviasi absolut dari kebutuhan kalori waktu makan.
     *   Deviasi kecil = menu lebih cocok secara energi.
     */
    private function collectRawValues(Collection $menus, float $kebutuhanKalori): array
    {
        $raw = [];

        foreach ($menus as $menu) {
            $gizi = $menu->kandunganGizi;
            if (!$gizi) continue;

            $raw[] = [
                'menu_id'         => $menu->id,
                'nama_menu'       => $menu->nama_menu,
                'jenis_menu'      => $menu->jenis_menu,
                'kalori'          => abs((float) $gizi->energi_kkal - $kebutuhanKalori),
                'karbohidrat'     => (float) $gizi->karbohidrat_gram,
                'protein'         => (float) $gizi->protein_gram,
                'serat'           => (float) $gizi->serat_gram,
                'indeks_glikemik' => (float) ($gizi->indeks_glikemik ?? 55),
            ];
        }

        return $raw;
    }

    // ── HELPER: MIN-MAX PER KRITERIA ─────────────────────────────

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

    // ── HELPER: CONSISTENCY RATIO ────────────────────────────────

    /**
     * CR = CI / RI,  CI = (λmax - n) / (n - 1)
     * Syarat valid: CR < 0.1
     */
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

        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $rowSum = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $rowSum += $matrix[$i][$j] / $colSum[$j];
            }
            $weights[$i] = $rowSum / $n;
        }

        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $ws = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $ws += $matrix[$i][$j] * $weights[$j];
            }
            $lambdaMax += $ws / $weights[$i];
        }
        $lambdaMax /= $n;

        $ri = [1 => 0.0, 2 => 0.0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32];
        $ci = ($lambdaMax - $n) / ($n - 1);
        $cr = $ci / ($ri[$n] ?? 1.12);

        return round(abs($cr), 4);
    }

    // ── HELPER: TFN LOOKUP ────────────────────────────────────────

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
