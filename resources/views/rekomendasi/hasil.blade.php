{{-- resources/views/rekomendasi/hasil.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi Menu</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; color: #333; }
        h2   { color: #2c7a2c; }
        .info-box  { background: #f0f8f0; border-left: 4px solid #2c7a2c; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px; }
        .menu-card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
        .menu-card.top { border: 2px solid #2c7a2c; background: #f0fff0; }
        .badge-top  { background: #2c7a2c; color: white; font-size: 12px; padding: 2px 10px; border-radius: 12px; }
        .badge-rank { background: #eee; color: #555; font-size: 12px; padding: 2px 10px; border-radius: 12px; }
        .tag { display: inline-block; background: #e8f5e9; color: #2c7a2c; font-size: 12px; padding: 2px 8px; border-radius: 10px; margin: 2px; }
        .kalori { color: #555; font-size: 14px; margin: 6px 0; }
        .footer-note { margin-top: 30px; font-size: 13px; color: #888; border-top: 1px solid #eee; padding-top: 12px; }
        a.btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2c7a2c; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>

<h2>🍽️ Rekomendasi Menu untuk {{ $anak->nama }}</h2>

<div class="info-box">
    <strong>Waktu makan:</strong> {{ ucfirst(str_replace('_', ' ', $waktuMakan)) }}<br>
    <strong>Target kalori:</strong> {{ $kaloriTarget }} kkal
</div>

<p>Menu berikut dipilih berdasarkan <strong>keseimbangan gizi</strong> dan
<strong>kesesuaian kebutuhan kalori</strong> anak penderita Diabetes Mellitus Tipe 1.</p>

@foreach($ranked as $item)
    <div class="menu-card {{ $item['ranking'] === 1 ? 'top' : '' }}">

        {{-- Badge ranking --}}
        @if($item['ranking'] === 1)
            <span class="badge-top">⭐ Rekomendasi Utama</span>
        @else
            <span class="badge-rank">Pilihan {{ $item['ranking'] }}</span>
        @endif

        <h3 style="margin: 8px 0 4px">{{ $item['nama_menu'] }}</h3>

        {{-- Kalori dari data gizi --}}
        @php
            $menu = $menus->firstWhere('id', $item['menu_id']);
            $gizi = $menu?->kandunganGizi;
        @endphp

        @if($gizi)
            <p class="kalori">🔥 {{ number_format($gizi->energi_kkal, 0) }} kkal per porsi</p>

            {{-- Tag penjelasan sederhana --}}
            <div>
                @if(abs($gizi->energi_kkal - $kaloriTarget) <= 50)
                    <span class="tag">✅ Kalori mendekati kebutuhan</span>
                @elseif($gizi->energi_kkal < $kaloriTarget)
                    <span class="tag">⬇️ Kalori lebih rendah dari target</span>
                @else
                    <span class="tag">⬆️ Kalori lebih tinggi dari target</span>
                @endif

                @if($gizi->indeks_glikemik && $gizi->indeks_glikemik < 55)
                    <span class="tag">🩸 Indeks glikemik rendah</span>
                @elseif($gizi->indeks_glikemik && $gizi->indeks_glikemik <= 70)
                    <span class="tag">🩸 Indeks glikemik sedang</span>
                @endif

                @if($gizi->protein_gram >= 15)
                    <span class="tag">💪 Tinggi protein</span>
                @endif

                @if($gizi->serat_gram >= 4)
                    <span class="tag">🌿 Tinggi serat</span>
                @endif

                @if($gizi->karbohidrat_gram <= 50)
                    <span class="tag">📉 Karbohidrat terkontrol</span>
                @endif
            </div>
        @endif

    </div>
@endforeach

<p class="footer-note">
    ℹ️ Rekomendasi ini bersifat pendukung keputusan. Konsultasikan dengan dokter atau ahli gizi
    sebelum mengubah pola makan anak.
</p>

<a class="btn" href="{{ route('rekomendasi.index') }}">← Coba Lagi</a>

</body>
</html>
