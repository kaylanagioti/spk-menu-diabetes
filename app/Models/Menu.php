<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Menu — pilihan makanan untuk anak DM Tipe 1.
 *
 * Pendekatan CURATED DATASET:
 * Hanya menu yang sudah divalidasi ahli gizi sebagai layak untuk anak
 * DM Tipe 1 yang masuk ke database. Tidak ada penyaringan dinamis.
 */
class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu';

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'gambar',
        'image_url',
        'jenis_menu',
        'porsi_gram',
        'satuan_porsi',
        'is_active',
        'sumber_resep',
    ];

    protected function casts(): array
    {
        return [
            'porsi_gram' => 'decimal:2',
            'is_active'  => 'boolean',
        ];
    }

    // ── RELATIONSHIPS ─────────────────────────────────────────

    public function kandunganGizi(): HasOne
    {
        return $this->hasOne(KandunganGizi::class);
    }
}
