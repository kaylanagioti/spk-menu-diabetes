<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anak', function (Blueprint $table) {
            $table->dropColumn([
                'berat_badan',
                'tinggi_badan',
                'tingkat_aktivitas'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('anak', function (Blueprint $table) {
            $table->decimal('berat_badan');
            $table->decimal('tinggi_badan');
            $table->string('tingkat_aktivitas');
        });
    }
};
