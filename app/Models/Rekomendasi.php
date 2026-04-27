<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = [
        'anak_id',
        'menu_id',
        'tanggal_rekomendasi',
        'waktu_makan',
        'bobot_kriteria',
        'nilai_preferensi',
        'ranking',
        'kebutuhan_kalori_harian',
        'consistency_ratio',
        'status',
        'catatan_sistem',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rekomendasi'     => 'date',
            'bobot_kriteria'          => 'array',
            'nilai_preferensi'        => 'decimal:6',
            'kebutuhan_kalori_harian' => 'decimal:2',
            'consistency_ratio'       => 'decimal:4',
            'ranking'                 => 'integer',
        ];
    }

    // ── RELATIONSHIPS ─────────────────────────────────────────────

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Anak::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
