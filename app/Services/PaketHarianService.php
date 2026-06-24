<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;

/**
 * PaketHarianService
 *
 * Membentuk 3 paket menu harian kandidat untuk dievaluasi oleh FuzzyAhpService.
 *
 * Mekanisme pembentukan paket:
 *   Untuk tiap slot waktu makan, menu diurutkan berdasarkan deviasi kalori
 *   terhadap target distribusi AKG. Tiga menu dengan deviasi terkecil
 *   dialokasikan masing-masing ke Paket A, B, dan C.
 *   Ini adalah prosedur berbasis AKG, bukan metode penelitian tersendiri.
 *
 * Catatan: service ini tidak melakukan ranking — ranking dilakukan oleh
 * FuzzyAhpService berdasarkan agregasi 4 kriteria gizi.
 */
class PaketHarianService
{
    private const SLOTS = [
        'sarapan',
        'snack_pagi',
        'makan_siang',
        'snack_sore',
        'makan_malam',
        'snack_malam',
    ];

    private const JUMLAH_PAKET = 3;

    /**
     * Generate 3 paket kandidat.
     *
     * @param  array<string, float>  $distribusiKalori  ['sarapan' => 400, ...]
     * @return array  Format tiap paket:
     *   [
     *     'id_paket'   => 'A',
     *     'menus'      => ['sarapan' => Menu|null, ...],
     *     'total_gizi' => ['kalori' => x, 'karbohidrat' => y, 'protein' => z, 'serat' => w]
     *   ]
     */
    public function generatePaketKandidat(array $distribusiKalori): array
    {
        // Ambil top-3 menu per slot (diurutkan deviasi kalori terkecil)
        $topMenuPerSlot = [];
        foreach (self::SLOTS as $slot) {
            $target = $distribusiKalori[$slot] ?? 0;
            $topMenuPerSlot[$slot] = $this->cariTopMenuSlot($slot, $target, self::JUMLAH_PAKET);
        }

        $labels = ['A', 'B', 'C'];
        $paket  = [];

        for ($i = 0; $i < self::JUMLAH_PAKET; $i++) {
            $menusPaket   = [];
            $totalKalori  = 0.0;
            $totalKarbo   = 0.0;
            $totalProtein = 0.0;
            $totalSerat   = 0.0;

            foreach (self::SLOTS as $slot) {
                $kandidat = $topMenuPerSlot[$slot];

                if ($kandidat->isEmpty()) {
                    $menusPaket[$slot] = null;
                    continue;
                }

                // Paket ke-i mengambil menu ke-i (modulo jika kurang dari 3)
                $menu = $kandidat[$i % $kandidat->count()];
                $menusPaket[$slot] = $menu;

                $gizi = $menu->kandunganGizi;
                if ($gizi) {
                    $totalKalori  += (float) $gizi->energi_kkal;
                    $totalKarbo   += (float) $gizi->karbohidrat_gram;
                    $totalProtein += (float) $gizi->protein_gram;
                    $totalSerat   += (float) $gizi->serat_gram;
                }
            }

            $paket[] = [
                'id_paket'   => $labels[$i],
                'menus'      => $menusPaket,
                'total_gizi' => [
                    'kalori'      => round($totalKalori, 2),
                    'karbohidrat' => round($totalKarbo, 2),
                    'protein'     => round($totalProtein, 2),
                    'serat'       => round($totalSerat, 2),
                ],
            ];
        }

        return $paket;
    }

    /**
     * Untuk satu slot, ambil top-N menu berdasarkan deviasi kalori terkecil
     * terhadap target distribusi AKG slot tersebut.
     */
    private function cariTopMenuSlot(string $slot, float $targetKalori, int $topN): Collection
    {
        return Menu::with('kandunganGizi')
            ->where('jenis_menu', $slot)
            ->where('is_active', true)
            ->get()
            ->filter(fn(Menu $m) => $m->kandunganGizi !== null)
            ->sortBy(fn(Menu $m) => abs((float) $m->kandunganGizi->energi_kkal - $targetKalori))
            ->take($topN)
            ->values();
    }
}
