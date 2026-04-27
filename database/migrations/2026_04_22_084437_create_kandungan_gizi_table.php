<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kandungan_gizi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                  ->unique()
                  ->constrained('menu')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('energi_kkal', 8, 2);
            $table->decimal('karbohidrat_gram', 7, 2);
            $table->decimal('protein_gram', 7, 2);
            $table->decimal('lemak_gram', 7, 2);
            $table->decimal('serat_gram', 7, 2)->default(0);
            $table->unsignedTinyInteger('indeks_glikemik')->nullable();
            $table->decimal('gula_gram', 7, 2)->default(0);
            $table->string('sumber_data', 100)->nullable()->comment('Contoh: TKPI 2017');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kandungan_gizi');
    }
};
