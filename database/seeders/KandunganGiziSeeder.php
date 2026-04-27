<?php

namespace Database\Seeders;

use App\Models\KandunganGizi;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class KandunganGiziSeeder extends Seeder
{
    /**
     * Data nilai gizi per menu.
     * Sumber: Tabel Komposisi Pangan Indonesia (TKPI) 2017
     *
     * Catatan untuk Fuzzy AHP:
     * Kolom-kolom ini adalah KRITERIA penilaian alternatif.
     * - energi_kkal      : kriteria benefit (sesuai kebutuhan kalori)
     * - karbohidrat_gram : kriteria cost (semakin rendah semakin baik untuk DM)
     * - protein_gram     : kriteria benefit
     * - lemak_gram       : kriteria cost
     * - serat_gram       : kriteria benefit (memperlambat absorpsi glukosa)
     * - indeks_glikemik  : kriteria cost (kritis untuk DM Tipe 1)
     * - gula_gram        : kriteria cost
     */
    public function run(): void
    {
        $data = [

            // ── SARAPAN ───────────────────────────────────────────────
            'Nasi Merah dengan Telur Rebus' => [
                'energi_kkal'      => 320.00,
                'karbohidrat_gram' => 52.00,
                'protein_gram'     => 14.00,
                'lemak_gram'       => 7.00,
                'serat_gram'       => 2.50,
                'indeks_glikemik'  => 55,
                'gula_gram'        => 1.00,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Bubur Ayam Tanpa Kerupuk' => [
                'energi_kkal'      => 280.00,
                'karbohidrat_gram' => 45.00,
                'protein_gram'     => 15.00,
                'lemak_gram'       => 5.00,
                'serat_gram'       => 1.00,
                'indeks_glikemik'  => 68,
                'gula_gram'        => 1.50,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Roti Gandum dengan Telur Dadar' => [
                'energi_kkal'      => 295.00,
                'karbohidrat_gram' => 38.00,
                'protein_gram'     => 16.00,
                'lemak_gram'       => 8.00,
                'serat_gram'       => 4.00,
                'indeks_glikemik'  => 50,
                'gula_gram'        => 3.00,
                'sumber_data'      => 'TKPI 2017',
            ],

            // ── MAKAN SIANG ───────────────────────────────────────────
            'Nasi Merah dengan Ayam Rebus dan Sayur Bayam' => [
                'energi_kkal'      => 480.00,
                'karbohidrat_gram' => 62.00,
                'protein_gram'     => 28.00,
                'lemak_gram'       => 10.00,
                'serat_gram'       => 3.50,
                'indeks_glikemik'  => 55,
                'gula_gram'        => 2.00,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Nasi Merah dengan Tempe Kukus dan Tumis Kangkung' => [
                'energi_kkal'      => 460.00,
                'karbohidrat_gram' => 65.00,
                'protein_gram'     => 22.00,
                'lemak_gram'       => 11.00,
                'serat_gram'       => 5.00,
                'indeks_glikemik'  => 52,
                'gula_gram'        => 2.50,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Nasi Merah dengan Ikan Kukus dan Brokoli' => [
                'energi_kkal'      => 455.00,
                'karbohidrat_gram' => 60.00,
                'protein_gram'     => 30.00,
                'lemak_gram'       => 8.00,
                'serat_gram'       => 4.00,
                'indeks_glikemik'  => 54,
                'gula_gram'        => 1.50,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Nasi Merah dengan Tahu Bakar dan Sup Wortel' => [
                'energi_kkal'      => 430.00,
                'karbohidrat_gram' => 63.00,
                'protein_gram'     => 18.00,
                'lemak_gram'       => 9.00,
                'serat_gram'       => 4.50,
                'indeks_glikemik'  => 53,
                'gula_gram'        => 3.00,
                'sumber_data'      => 'TKPI 2017',
            ],

            // ── MAKAN MALAM ───────────────────────────────────────────
            'Nasi Merah dengan Ayam Panggang dan Tumis Buncis' => [
                'energi_kkal'      => 420.00,
                'karbohidrat_gram' => 55.00,
                'protein_gram'     => 27.00,
                'lemak_gram'       => 9.00,
                'serat_gram'       => 3.50,
                'indeks_glikemik'  => 55,
                'gula_gram'        => 1.50,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Nasi Merah dengan Telur Rebus dan Sup Sayuran' => [
                'energi_kkal'      => 390.00,
                'karbohidrat_gram' => 55.00,
                'protein_gram'     => 20.00,
                'lemak_gram'       => 8.00,
                'serat_gram'       => 4.00,
                'indeks_glikemik'  => 54,
                'gula_gram'        => 2.00,
                'sumber_data'      => 'TKPI 2017',
            ],
            'Nasi Merah dengan Tempe Bacem dan Sayur Asem' => [
                'energi_kkal'      => 410.00,
                'karbohidrat_gram' => 60.00,
                'protein_gram'     => 19.00,
                'lemak_gram'       => 10.00,
                'serat_gram'       => 4.50,
                'indeks_glikemik'  => 53,
                'gula_gram'        => 3.50,
                'sumber_data'      => 'TKPI 2017',
            ],
        ];

        foreach ($data as $namaMenu => $gizi) {
            $menu = Menu::where('nama_menu', $namaMenu)->first();

            if ($menu) {
                KandunganGizi::create(array_merge(
                    ['menu_id' => $menu->id],
                    $gizi
                ));
            }
        }
    }
}
