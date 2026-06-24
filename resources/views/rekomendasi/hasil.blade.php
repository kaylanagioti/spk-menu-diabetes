<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rencana Menu Harian — {{ $anak->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau-soft: #6BAF92;
            --hijau-tua: #3D7A5D;
            --krem: #F5F8F4;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--krem); color: #2c3e2d; }
        h1, h2, h3, h4 { font-family: 'DM Serif Display', serif; }
        .hero { background: linear-gradient(135deg, var(--hijau-soft), var(--hijau-tua)); color: #fff; padding: 40px 0; border-radius: 0 0 28px 28px; }
        .meal-card { border: none; border-radius: 18px; box-shadow: 0 6px 22px rgba(0,0,0,.06); overflow: hidden; height: 100%; transition: transform .15s; }
        .meal-card:hover { transform: translateY(-4px); }
        .meal-img { height: 160px; object-fit: cover; width: 100%; background: #e8efe9; }
        .meal-time { font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; color: var(--hijau-tua); font-weight: 700; }
        .badge-kalori { background: var(--hijau-soft); }
        .total-box { background: var(--hijau-tua); color: #fff; border-radius: 18px; padding: 24px; }
        .tag { font-size: .72rem; padding: 3px 9px; border-radius: 20px; background: #e3efe8; color: var(--hijau-tua); margin-right: 4px; display:inline-block; margin-bottom:4px; }
    </style>
</head>
<body>

<div class="hero text-center mb-4">
    <div class="container">
        <h1 class="mb-1">Rencana Menu Harian</h1>
        <p class="lead mb-0">untuk <strong>{{ $anak->nama }}</strong> ({{ $anak->usia }} tahun)</p>
    </div>
</div>

<div class="container pb-5">

    {{-- Ringkasan kebutuhan kalori --}}
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="total-box text-center">
                <div class="row">
                    <div class="col-6 border-end">
                        <div style="font-size:.85rem; opacity:.85">Kebutuhan Kalori Harian</div>
                        <div style="font-size:1.8rem; font-weight:700">{{ number_format($totalKalori, 0) }} <small>kkal</small></div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:.85rem; opacity:.85">Jumlah Paket Rekomendasi</div>
                        <div style="font-size:1.8rem; font-weight:700">
                            {{ count($ranked) }}
                            <small>paket</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === Rekomendasi Menu Harian === --}}
    <h2 class="text-center mb-4">Hasil Rekomendasi Paket Menu Harian</h2>

    <div class="row g-4">

    @foreach($ranked as $item)

        @php
            $paket = $item['paket'];
            $gizi = $paket['total_gizi'];
        @endphp

        <div class="col-12">

            <div class="meal-card p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h3 class="mb-0">
                        @if($item['ranking'] == 1)
                            🏆 Peringkat 1
                        @elseif($item['ranking'] == 2)
                            🥈 Peringkat 2
                        @else
                            🥉 Peringkat 3
                        @endif
                    </h3>

                    <span class="badge bg-success">
                        Skor {{ number_format($item['skor'], 6) }}
                    </span>

                </div>

                <div class="row mb-3">

                    <div class="col-md-3">
                        <strong>Kalori</strong><br>
                        {{ number_format($gizi['kalori'],0) }} kkal
                    </div>

                    <div class="col-md-3">
                        <strong>Karbohidrat</strong><br>
                        {{ number_format($gizi['karbohidrat'],1) }} g
                    </div>

                    <div class="col-md-3">
                        <strong>Protein</strong><br>
                        {{ number_format($gizi['protein'],1) }} g
                    </div>

                    <div class="col-md-3">
                        <strong>Serat</strong><br>
                        {{ number_format($gizi['serat'],1) }} g
                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th>Waktu Makan</th>
                            <th>Menu</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($paket['menus'] as $slot => $menu)

                            <tr>

                                <td>
                                    {{ $labelWaktu[$slot] ?? ucfirst($slot) }}
                                </td>

                                <td>
                                    {{ $menu->nama_menu ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Tombol pilih paket --}}
                <form action="{{ route('rekomendasi.pilih') }}"
                      method="POST"
                      class="mt-4">

                    @csrf

                    <input type="hidden"
                           name="rekomendasi_id"
                           value="{{ $rekomendasiIds[$item['ranking']] ?? '' }}">

                    <input type="hidden"
                           name="anak_id"
                           value="{{ $anak->id }}">

                    <input type="hidden"
                           name="ranking_dipilih"
                           value="{{ $item['ranking'] }}">

                    <button type="submit"
                            class="btn btn-success">
                        Pilih Paket Ini
                    </button>

                </form>

            </div>

        </div>

    @endforeach

    </div>

    {{-- Penjelasan gizi singkat --}}
    <div class="row mt-5">
        <div class="col-md-9 mx-auto">
            <div class="card meal-card p-4">
                <h4 class="mb-3">Tentang Rencana Menu Ini</h4>
                <p style="font-size:.95rem; line-height:1.7">
                    Rencana ini disusun berdasarkan kebutuhan energi harian
                    <strong>{{ number_format($totalKalori, 0) }} kkal</strong>, dibagi ke enam waktu makan
                    (sarapan 20%, snack pagi 10%, makan siang 25%, snack sore 10%, makan malam 25%, snack malam 10%).
                    Pembagian porsi kecil namun sering bertujuan menjaga
                    <strong>kestabilan gula darah</strong> sepanjang hari — penting bagi anak dengan
                    Diabetes Mellitus Tipe 1.
                </p>
                <p style="font-size:.95rem; line-height:1.7" class="mb-0">
                        Paket menu direkomendasikan berdasarkan kebutuhan energi harian serta 
                        evaluasi kandungan karbohidrat, protein, dan serat menggunakan metode
                        Fuzzy AHP. Sistem memberikan tiga alternatif paket menu yang dapat
                        dipilih sesuai kebutuhan anak.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('rekomendasi.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            Buat Rencana Baru
        </a>
    </div>

    <p class="text-center text-muted mt-4" style="font-size:.85rem">
        Rekomendasi ini bersifat bantuan/edukasi, bukan pengganti nasihat medis.
        Konsultasikan tetap dengan ahli gizi atau dokter sebelum menerapkan.
    </p>

</div>

</body>
</html>
