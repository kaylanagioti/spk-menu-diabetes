<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Anak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anak';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'catatan',
    ];


    public function getUsiaAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    public function rekomendasies(): HasMany
    {
        return $this->hasMany(Rekomendasi::class);
    }
}
