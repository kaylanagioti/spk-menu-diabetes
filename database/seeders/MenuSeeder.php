<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Seeder menu — CURATED DATASET (validasi ahli gizi).
 *
 * Seluruh menu di sini SUDAH dinyatakan layak untuk anak DM Tipe 1.
 * Makanan tidak sehat (gorengan, snack manis, karbohidrat sederhana,
 * makanan olahan berat) TIDAK dimasukkan ke database.
 *
 * Jumlah menu per slot ≥ 3 agar PaketHarianService dapat menghasilkan
 * 3 paket harian yang divers (tidak duplikat).
 *
 * Sumber: TKPI 2020 (Tabel Komposisi Pangan Indonesia, Kemenkes RI 2020).
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // ── SARAPAN (20%) ────────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Telur Rebus dan Tumis Bayam',
                'deskripsi'    => 'Nasi beras merah, telur rebus, tumis bayam.',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 250,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-telur-bayam.jpg',
            ],
            [
                'nama_menu'    => 'Roti Gandum dengan Telur Dadar',
                'deskripsi'    => 'Roti gandum utuh dengan telur dadar minim minyak.',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 200,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/roti-gandum-telur-dadar.jpg',
            ],
            [
                'nama_menu'    => 'Oatmeal dengan Pisang dan Susu Rendah Lemak',
                'deskripsi'    => 'Oat utuh dimasak dengan susu rendah lemak dan irisan pisang.',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 250,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/oatmeal-pisang.jpg',
            ],

            // ── SNACK PAGI (10%) ─────────────────────────────
            [
                'nama_menu'    => 'Apel Potong',
                'deskripsi'    => 'Apel segar potong, sumber serat.',
                'jenis_menu'   => 'snack_pagi',
                'porsi_gram'   => 150,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/apel-potong.jpg',
            ],
            [
                'nama_menu'    => 'Yogurt Plain dengan Pisang',
                'deskripsi'    => 'Yogurt tanpa gula tambahan dengan irisan pisang.',
                'jenis_menu'   => 'snack_pagi',
                'porsi_gram'   => 150,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/yogurt-pisang.jpg',
            ],
            [
                'nama_menu'    => 'Pir Potong',
                'deskripsi'    => 'Buah pir segar potong, kaya serat.',
                'jenis_menu'   => 'snack_pagi',
                'porsi_gram'   => 150,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/pir-potong.jpg',
            ],

            // ── MAKAN SIANG (25%) ────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Ayam Rebus dan Sayur Bayam',
                'deskripsi'    => 'Nasi beras merah, ayam rebus tanpa kulit, sayur bayam bening.',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 400,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-ayam-bayam.jpg',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Ikan Kukus dan Brokoli',
                'deskripsi'    => 'Nasi beras merah, ikan kukus bumbu kuning, brokoli rebus.',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 400,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-ikan-brokoli.jpg',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Tempe Kukus dan Tumis Kangkung',
                'deskripsi'    => 'Nasi beras merah, tempe kukus, tumis kangkung minim minyak.',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 380,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-tempe-kangkung.jpg',
            ],

            // ── SNACK SORE (10%) ─────────────────────────────
            [
                'nama_menu'    => 'Kacang Tanah Rebus',
                'deskripsi'    => 'Kacang tanah rebus, sumber protein dan serat.',
                'jenis_menu'   => 'snack_sore',
                'porsi_gram'   => 100,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/kacang-rebus.jpg',
            ],
            [
                'nama_menu'    => 'Jagung Rebus',
                'deskripsi'    => 'Jagung manis rebus, karbohidrat kompleks.',
                'jenis_menu'   => 'snack_sore',
                'porsi_gram'   => 120,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/jagung-rebus.jpg',
            ],
            [
                'nama_menu'    => 'Ubi Kukus',
                'deskripsi'    => 'Ubi jalar kukus, karbohidrat kompleks.',
                'jenis_menu'   => 'snack_sore',
                'porsi_gram'   => 120,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/ubi-kukus.jpg',
            ],

            // ── MAKAN MALAM (25%) ────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Ayam Panggang dan Tumis Buncis',
                'deskripsi'    => 'Nasi beras merah, ayam panggang tanpa kulit, tumis buncis.',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 350,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-ayam-buncis.jpg',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Ikan Pepes dan Sayur Bening',
                'deskripsi'    => 'Nasi beras merah, ikan pepes (kukus daun), sayur bening labu.',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 380,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-ikan-pepes.jpg',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Tahu Kukus dan Sayur Sop',
                'deskripsi'    => 'Nasi beras merah, tahu kukus, sayur sop wortel-kentang.',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 380,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/nasi-merah-tahu-sop.jpg',
            ],

            // ── SNACK MALAM (10%) ────────────────────────────
            [
                'nama_menu'    => 'Susu Rendah Lemak',
                'deskripsi'    => 'Segelas susu rendah lemak tanpa gula tambahan.',
                'jenis_menu'   => 'snack_malam',
                'porsi_gram'   => 200,
                'satuan_porsi' => 'ml',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/susu-rendah-lemak.jpg',
            ],
            [
                'nama_menu'    => 'Pepaya Potong',
                'deskripsi'    => 'Pepaya segar potong, sumber serat dan vitamin.',
                'jenis_menu'   => 'snack_malam',
                'porsi_gram'   => 150,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/pepaya-potong.jpg',
            ],
            [
                'nama_menu'    => 'Alpukat Potong',
                'deskripsi'    => 'Alpukat segar potong, lemak sehat dan serat tinggi.',
                'jenis_menu'   => 'snack_malam',
                'porsi_gram'   => 100,
                'satuan_porsi' => 'gram',
                'sumber_resep' => 'TKPI 2020',
                'is_active'    => true,
                'image_url'    => '/images/menu/alpukat-potong.jpg',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
