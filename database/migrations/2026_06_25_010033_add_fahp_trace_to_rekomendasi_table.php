<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekomendasi', function (Blueprint $table) {
            $table->json('fahp_trace')
                  ->nullable()
                  ->after('consistency_ratio');
        });
    }

    public function down(): void
    {
        Schema::table('rekomendasi', function (Blueprint $table) {
            $table->dropColumn('fahp_trace');
        });
    }
};
