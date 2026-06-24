<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Redesign tabel rekomendasi.
 *
 * Skema baru: 1 row = 1 paket menu harian lengkap (6 waktu makan).
 * Sebelumnya: 1 row = 1 menu untuk 1 waktu makan.
 *
 * Kolom waktu_makan dihapus karena 1 paket sudah mencakup keenam waktu.
 * Setiap waktu makan punya kolom FK ke menu (sarapan, snack_pagi, dst.)
 *
 * PERINGATAN: data lama tabel rekomendasi akan HILANG.
 * Jalankan migrate:fresh --seed setelah migrasi ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel lama secara total — desain baru tidak kompatibel
        Schema::dropIfExists('rekomendasi');

        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();

            // Identitas paket harian
            $table->foreignId('anak_id')->constrained('anak')->cascadeOnDelete();
            $table->date('tanggal_rekomendasi');
            $table->unsignedTinyInteger('ranking')->comment('1, 2, atau 3');

            // 6 slot menu (FK ke tabel menu, nullable kalau kategori kosong)
            $table->foreignId('menu_sarapan_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->foreignId('menu_snack_pagi_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->foreignId('menu_makan_siang_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->foreignId('menu_snack_sore_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->foreignId('menu_makan_malam_id')->nullable()->constrained('menu')->nullOnDelete();
            $table->foreignId('menu_snack_malam_id')->nullable()->constrained('menu')->nullOnDelete();

            // Snapshot perhitungan
            $table->decimal('kebutuhan_kalori_harian', 7, 2);
            $table->decimal('total_kalori_paket', 7, 2);
            $table->decimal('nilai_preferensi', 8, 6)->comment('Skor Fuzzy AHP paket');
            $table->json('bobot_kriteria')->comment('{kalori, karbohidrat, protein, serat}');
            $table->decimal('consistency_ratio', 6, 4)->nullable()->comment('Untuk audit admin');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['anak_id', 'tanggal_rekomendasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
