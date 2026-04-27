<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();

            $table->string('nama_menu', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('jenis_menu', ['sarapan', 'makan_siang', 'makan_malam']);
            $table->decimal('porsi_gram', 7, 2)->comment('gram');
            $table->string('satuan_porsi', 50)->default('gram');
            $table->boolean('is_active')->default(true);
            $table->string('sumber_resep', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
