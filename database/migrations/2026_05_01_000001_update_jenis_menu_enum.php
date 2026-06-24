<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Update enum kolom jenis_menu (tabel menu) untuk mendukung
 * rencana makan harian penuh dengan 6 waktu makan.
 *
 * Validasi ahli gizi: anak DM Tipe 1 sebaiknya makan dalam porsi kecil
 * namun sering (3 makan utama + 3 snack) untuk menjaga gula darah stabil.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE menu
            MODIFY COLUMN jenis_menu ENUM(
                'sarapan',
                'snack_pagi',
                'makan_siang',
                'snack_sore',
                'makan_malam',
                'snack_malam'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE menu
            MODIFY COLUMN jenis_menu ENUM(
                'sarapan',
                'makan_siang',
                'makan_malam'
            ) NOT NULL
        ");
    }
};
