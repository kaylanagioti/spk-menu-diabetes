<?php

namespace Database\Seeders;

use App\Models\KandunganGizi;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Seeder kandungan gizi — placeholder.
 *
 * ─────────────────────────────────────────────────────────────────
 *  PENTING: ANGKA DI BAWAH HARUS DIVERIFIKASI DARI TKPI 2020
 * ─────────────────────────────────────────────────────────────────
 *
 * Angka per porsi di sini adalah ESTIMASI awal untuk testing logika DSS.
 * Sebelum sidang, ganti nilai gizi tiap menu dengan komposisi resmi dari
 * Tabel Komposisi Pangan Indonesia (TKPI 2020, Kemenkes RI).
 *
 * Cara menghitung per porsi:
 *   nilai_per_porsi = (nilai_per_100g_TKPI x porsi_gram_menu) / 100
 *
 * Catatan: TKPI 2020 TIDAK memuat Indeks Glikemik — kriteria DSS final
 * menggunakan 4 kriteria saja (Kalori, Karbohidrat, Protein, Serat),
 * semua tersedia di TKPI 2020.
 *
 * Urutan array HARUS sama dengan urutan menu di MenuSeeder agar menu_id cocok.
 */
class KandunganGiziSeeder extends Seeder
{
    public function run(): void
    {
        // Format: [energi_kkal, karbohidrat_gram, protein_gram, serat_gram]
        // (lemak & micronutrient bisa ditambah nanti jika dibutuhkan)
        $gizi = [
            // SARAPAN
            [350, 48, 15,  5.0],   // Nasi Merah + Telur Rebus + Bayam
            [280, 38, 16,  5.0],   // Roti Gandum + Telur Dadar
            [300, 50, 10,  6.0],   // Oatmeal + Pisang + Susu

            // SNACK PAGI
            [95,  25, 0.5, 4.0],   // Apel Potong
            [150, 24, 6,   2.5],   // Yogurt + Pisang
            [90,  24, 0.5, 5.5],   // Pir Potong

            // MAKAN SIANG
            [480, 65, 28,  6.5],   // Nasi Merah + Ayam Rebus + Bayam
            [460, 60, 32,  5.8],   // Nasi Merah + Ikan Kukus + Brokoli
            [510, 70, 22,  7.0],   // Nasi Merah + Tempe Kukus + Kangkung

            // SNACK SORE
            [170, 12, 8,   4.0],   // Kacang Tanah Rebus
            [130, 28, 4,   3.5],   // Jagung Rebus
            [135, 31, 2,   3.8],   // Ubi Kukus

            // MAKAN MALAM
            [430, 55, 30,  6.0],   // Nasi Merah + Ayam Panggang + Buncis
            [450, 58, 31,  6.2],   // Nasi Merah + Ikan Pepes + Sayur Bening
            [400, 60, 18,  5.5],   // Nasi Merah + Tahu Kukus + Sop

            // SNACK MALAM
            [120, 12, 8,   0],     // Susu Rendah Lemak
            [60,  15, 0.5, 2.5],   // Pepaya Potong
            [160, 9,  2,   7.0],   // Alpukat Potong
        ];

        $menus = Menu::orderBy('id')->get();

        foreach ($menus as $i => $menu) {
            if (!isset($gizi[$i])) continue;

            [$energi, $karbo, $protein, $serat] = $gizi[$i];

            KandunganGizi::create([
                'menu_id'          => $menu->id,
                'energi_kkal'      => $energi,
                'karbohidrat_gram' => $karbo,
                'protein_gram'     => $protein,
                'lemak_gram'       => 0,           // diisi dari TKPI 2020 nanti
                'serat_gram'       => $serat,
                'indeks_glikemik'  => null,        // ❌ tidak dipakai lagi (4 kriteria)
                'gula_gram'        => 0,           // tidak dipakai
                'sumber_data'      => 'TKPI 2020 (BELUM DIVERIFIKASI)',
            ]);
        }
    }
}
