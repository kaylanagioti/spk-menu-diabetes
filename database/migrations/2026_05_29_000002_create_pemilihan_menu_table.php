<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pemilihan_menu — menyimpan paket harian yang dipilih orang tua
 * setelah melihat 3 peringkat rekomendasi.
 *
 * Tujuan (validasi):
 * - Riwayat pemilihan untuk audit admin
 * - Analisis: peringkat mana yang paling sering dipilih
 * - Insight untuk perbaikan dataset menu di masa depan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemilihan_menu', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rekomendasi_id')->constrained('rekomendasi')->cascadeOnDelete();
            $table->foreignId('anak_id')->constrained('anak')->cascadeOnDelete();
            $table->unsignedTinyInteger('ranking_dipilih')->comment('1, 2, atau 3');
            $table->timestamp('dipilih_pada')->useCurrent();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['anak_id', 'dipilih_pada']);
            $table->index('ranking_dipilih');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemilihan_menu');
    }
};
