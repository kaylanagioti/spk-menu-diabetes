<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KandunganGizi extends Model
{
    use HasFactory;

    protected $table = 'kandungan_gizi';

    protected $fillable = [
        'menu_id',
        'energi_kkal',
        'karbohidrat_gram',
        'protein_gram',
        'lemak_gram',
        'serat_gram',
        'indeks_glikemik',
        'gula_gram',
        'sumber_data',
    ];

    protected function casts(): array
    {
        return [
            'energi_kkal'      => 'decimal:2',
            'karbohidrat_gram' => 'decimal:2',
            'protein_gram'     => 'decimal:2',
            'lemak_gram'       => 'decimal:2',
            'serat_gram'       => 'decimal:2',
            'gula_gram'        => 'decimal:2',
        ];
    }

    // ── RELATIONSHIPS ─────────────────────────────────────────────

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
