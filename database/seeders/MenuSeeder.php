<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [

            // ── SARAPAN ───────────────────────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Telur Rebus',
                'deskripsi'    => 'Nasi merah porsi kecil disajikan dengan telur rebus',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 250,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Bubur Ayam Tanpa Kerupuk',
                'deskripsi'    => 'Bubur beras putih dengan suwiran ayam rebus dan sedikit kecap',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 300,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Roti Gandum dengan Telur Dadar',
                'deskripsi'    => 'Dua lembar roti gandum utuh dengan telur dadar tanpa minyak',
                'jenis_menu'   => 'sarapan',
                'porsi_gram'   => 200,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],

            // ── MAKAN SIANG ───────────────────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Ayam Rebus dan Sayur Bayam',
                'deskripsi'    => 'Nasi merah, ayam rebus tanpa kulit, sayur bayam rebus',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 400,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Tempe Kukus dan Tumis Kangkung',
                'deskripsi'    => 'Nasi merah, tempe kukus, tumis kangkung sedikit minyak',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 380,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Ikan Kukus dan Brokoli',
                'deskripsi'    => 'Nasi merah, ikan kakap kukus, brokoli rebus',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 400,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Tahu Bakar dan Sup Wortel',
                'deskripsi'    => 'Nasi merah, tahu bakar tanpa minyak, sup wortel dan kentang',
                'jenis_menu'   => 'makan_siang',
                'porsi_gram'   => 420,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],

            // ── MAKAN MALAM ───────────────────────────────────────────
            [
                'nama_menu'    => 'Nasi Merah dengan Ayam Panggang dan Tumis Buncis',
                'deskripsi'    => 'Nasi merah, ayam panggang tanpa kulit, tumis buncis',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 350,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Telur Rebus dan Sup Sayuran',
                'deskripsi'    => 'Nasi merah, dua butir telur rebus, sup sayuran campur',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 350,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
            [
                'nama_menu'    => 'Nasi Merah dengan Tempe Bacem dan Sayur Asem',
                'deskripsi'    => 'Nasi merah, tempe bacem rendah gula, sayur asem',
                'jenis_menu'   => 'makan_malam',
                'porsi_gram'   => 360,
                'satuan_porsi' => 'gram',
                'is_active'    => true,
                'sumber_resep' => 'TKPI 2017',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
