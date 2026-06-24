<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemilihanMenu extends Model
{
    use HasFactory;

    protected $table = 'pemilihan_menu';

    protected $fillable = [
        'rekomendasi_id',
        'anak_id',
        'ranking_dipilih',
        'dipilih_pada',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'dipilih_pada'    => 'datetime',
            'ranking_dipilih' => 'integer',
        ];
    }

    // ── RELATIONSHIPS ─────────────────────────────────────────

    public function rekomendasi(): BelongsTo
    {
        return $this->belongsTo(Rekomendasi::class);
    }

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Anak::class);
    }
}
