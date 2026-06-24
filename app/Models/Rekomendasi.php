<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 1 record Rekomendasi = 1 paket menu harian lengkap (6 waktu makan).
 * Tiap rekomendasi punya ranking 1–3 (hasil Fuzzy AHP).
 */
class Rekomendasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekomendasi';

    protected $fillable = [
        'anak_id',
        'tanggal_rekomendasi',
        'ranking',
        'menu_sarapan_id',
        'menu_snack_pagi_id',
        'menu_makan_siang_id',
        'menu_snack_sore_id',
        'menu_makan_malam_id',
        'menu_snack_malam_id',
        'kebutuhan_kalori_harian',
        'total_kalori_paket',
        'nilai_preferensi',
        'bobot_kriteria',
        'consistency_ratio',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rekomendasi'     => 'date',
            'ranking'                 => 'integer',
            'kebutuhan_kalori_harian' => 'decimal:2',
            'total_kalori_paket'      => 'decimal:2',
            'nilai_preferensi'        => 'decimal:6',
            'bobot_kriteria'          => 'array',
            'consistency_ratio'       => 'decimal:4',
        ];
    }

    /** Daftar slot waktu makan dan FK kolomnya */
    public const SLOTS = [
        'sarapan'     => 'menu_sarapan_id',
        'snack_pagi'  => 'menu_snack_pagi_id',
        'makan_siang' => 'menu_makan_siang_id',
        'snack_sore'  => 'menu_snack_sore_id',
        'makan_malam' => 'menu_makan_malam_id',
        'snack_malam' => 'menu_snack_malam_id',
    ];

    // ── RELATIONSHIPS ─────────────────────────────────────────

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Anak::class);
    }

    public function menuSarapan(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_sarapan_id');
    }

    public function menuSnackPagi(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_snack_pagi_id');
    }

    public function menuMakanSiang(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_makan_siang_id');
    }

    public function menuSnackSore(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_snack_sore_id');
    }

    public function menuMakanMalam(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_makan_malam_id');
    }

    public function menuSnackMalam(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_snack_malam_id');
    }

    public function pemilihan(): HasMany
    {
        return $this->hasMany(PemilihanMenu::class);
    }

    // ── HELPERS ───────────────────────────────────────────────

    /**
     * Kembalikan semua menu paket sebagai assoc array
     * ['sarapan' => Menu, 'snack_pagi' => Menu, ...]
     */
    public function semuaMenu(): array
    {
        return [
            'sarapan'     => $this->menuSarapan,
            'snack_pagi'  => $this->menuSnackPagi,
            'makan_siang' => $this->menuMakanSiang,
            'snack_sore'  => $this->menuSnackSore,
            'makan_malam' => $this->menuMakanMalam,
            'snack_malam' => $this->menuSnackMalam,
        ];
    }

    /** Eager-load semua menu sekaligus dengan gizi */
    public function scopeWithAllMenus($query)
    {
        return $query->with([
            'menuSarapan.kandunganGizi',
            'menuSnackPagi.kandunganGizi',
            'menuMakanSiang.kandunganGizi',
            'menuSnackSore.kandunganGizi',
            'menuMakanMalam.kandunganGizi',
            'menuSnackMalam.kandunganGizi',
        ]);
    }
}
