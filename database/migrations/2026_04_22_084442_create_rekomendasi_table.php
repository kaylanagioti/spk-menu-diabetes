<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anak_id')
                  ->constrained('anak')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('menu_id')
                  ->constrained('menu')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->date('tanggal_rekomendasi');
            $table->enum('waktu_makan', ['sarapan', 'makan_siang', 'makan_malam']);
            $table->json('bobot_kriteria');
            $table->decimal('nilai_preferensi', 10, 6);
            $table->unsignedTinyInteger('ranking');
            $table->decimal('kebutuhan_kalori_harian', 8, 2)->comment('Snapshot TEE saat rekomendasi dibuat');
            $table->decimal('consistency_ratio', 5, 4)->nullable();
            $table->enum('status', ['aktif', 'riwayat'])->default('aktif');
            $table->text('catatan_sistem')->nullable();

            $table->timestamps();

            $table->index(['anak_id', 'tanggal_rekomendasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
