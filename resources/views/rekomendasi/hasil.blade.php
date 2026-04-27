{{-- resources/views/rekomendasi/hasil.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi — {{ $anak->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-dark:  #1a4731;
            --green-mid:   #2d7a4f;
            --green-light: #4caf80;
            --green-pale:  #e8f5ee;
            --cream:       #faf9f6;
            --text:        #1c2b22;
            --muted:       #6b8070;
            --gold:        #f59e0b;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
        }

        /* ── HEADER ── */
        .top-bar {
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            padding: 28px 0;
        }
        .top-bar h1 {
            font-family: 'DM Serif Display', serif;
            color: #fff;
            font-size: 1.9rem;
            margin: 0;
        }
        .top-bar .meta {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin-top: 4px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ── SUMMARY STRIP ── */
        .summary-strip {
            background: #fff;
            border-bottom: 1px solid #e5ede8;
            padding: 16px 0;
        }
        .summary-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .summary-icon {
            width: 38px; height: 38px;
            background: var(--green-pale);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .summary-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; }
        .summary-value { font-size: 0.95rem; font-weight: 600; color: var(--text); }

        /* ── SECTION ── */
        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: var(--green-dark);
            margin-bottom: 4px;
        }
        .section-sub {
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 24px;
        }

        /* ── MENU CARDS ── */
        .menu-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1.5px solid #e5ede8;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(26,71,49,0.1);
        }
        .menu-card.top-rank {
            border: 2px solid var(--green-mid);
            box-shadow: 0 6px 24px rgba(45,122,79,0.15);
        }

        .card-img-wrap {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: var(--green-pale);
        }
        .card-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .menu-card:hover .card-img-wrap img { transform: scale(1.05); }

        .rank-badge {
            position: absolute;
            top: 12px; left: 12px;
            background: var(--gold);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            display: flex; align-items: center; gap: 4px;
        }
        .rank-num {
            position: absolute;
            top: 12px; right: 12px;
            background: rgba(0,0,0,0.5);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .card-body-inner {
            padding: 18px 20px 20px;
        }
        .menu-name {
            font-weight: 600;
            font-size: 0.97rem;
            color: var(--text);
            margin-bottom: 8px;
            line-height: 1.35;
        }
        .kalori-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 12px;
        }
        .kalori-val { font-weight: 600; color: var(--green-dark); }

        .tag-wrap { display: flex; flex-wrap: wrap; gap: 5px; }
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: var(--green-pale);
            color: var(--green-dark);
            font-size: 0.72rem;
            padding: 3px 9px;
            border-radius: 20px;
            font-weight: 500;
        }

        /* ── DISCLAIMER ── */
        .disclaimer {
            background: #fff;
            border-left: 3px solid var(--green-light);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        footer {
            text-align: center;
            padding: 24px;
            font-size: 0.78rem;
            color: var(--muted);
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>🍽️ Hasil Rekomendasi</h1>
                <p class="meta">untuk <strong style="color:#fff">{{ $anak->nama }}</strong> — {{ ucfirst(str_replace('_', ' ', $waktuMakan)) }}</p>
            </div>
            <a href="{{ route('rekomendasi.index') }}" class="back-btn">← Coba Lagi</a>
        </div>
    </div>
</div>

<!-- SUMMARY STRIP -->
<div class="summary-strip">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <div class="summary-icon">👤</div>
                    <div>
                        <div class="summary-label">Nama Anak</div>
                        <div class="summary-value">{{ $anak->nama }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <div class="summary-icon">🎂</div>
                    <div>
                        <div class="summary-label">Usia</div>
                        <div class="summary-value">{{ $anak->usia }} tahun</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <div class="summary-icon">🔥</div>
                    <div>
                        <div class="summary-label">Total Kalori Harian</div>
                        <div class="summary-value">{{ $totalKalori }} kkal</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <div class="summary-icon">🎯</div>
                    <div>
                        <div class="summary-label">Target {{ ucfirst(str_replace('_',' ',$waktuMakan)) }}</div>
                        <div class="summary-value">{{ $kaloriTarget }} kkal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container py-5" style="max-width: 960px">

    <p class="section-title">Daftar Rekomendasi Menu</p>
    <p class="section-sub">
        Menu dipilih berdasarkan keseimbangan gizi dan kesesuaian kebutuhan kalori anak
        menggunakan metode <strong>Fuzzy AHP</strong>.
    </p>

    <div class="row g-4">
        @foreach($ranked as $item)
        @php
            $menu = $menus->firstWhere('id', $item['menu_id']);
            $gizi = $menu?->kandunganGizi;
            $imgUrl = $menu?->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80';
            $isTop  = $item['ranking'] === 1;
            $col    = $isTop ? 'col-12 col-md-6' : 'col-12 col-md-6 col-lg-4';
        @endphp

        <div class="{{ $col }}">
            <div class="menu-card {{ $isTop ? 'top-rank' : '' }}">

                <!-- Image -->
                <div class="card-img-wrap">
                    <img src="{{ $imgUrl }}"
                         alt="{{ $item['nama_menu'] }}"
                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'">

                    @if($isTop)
                        <span class="rank-badge">⭐ Rekomendasi Utama</span>
                    @else
                        <span class="rank-num">Pilihan {{ $item['ranking'] }}</span>
                    @endif
                </div>

                <!-- Body -->
                <div class="card-body-inner">
                    <div class="menu-name">{{ $item['nama_menu'] }}</div>

                    @if($gizi)
                    <div class="kalori-row">
                        🔥 <span class="kalori-val">{{ number_format($gizi->energi_kkal, 0) }} kkal</span>
                        <span>per porsi</span>
                    </div>

                    <div class="tag-wrap">
                        @if(abs($gizi->energi_kkal - $kaloriTarget) <= 60)
                            <span class="tag">✅ Kalori sesuai target</span>
                        @elseif($gizi->energi_kkal < $kaloriTarget)
                            <span class="tag">⬇️ Kalori lebih rendah</span>
                        @else
                            <span class="tag">⬆️ Kalori lebih tinggi</span>
                        @endif

                        @if($gizi->indeks_glikemik && $gizi->indeks_glikemik < 55)
                            <span class="tag">🩸 IG rendah</span>
                        @elseif($gizi->indeks_glikemik && $gizi->indeks_glikemik <= 70)
                            <span class="tag">🩸 IG sedang</span>
                        @endif

                        @if($gizi->protein_gram >= 15)
                            <span class="tag">💪 Tinggi protein</span>
                        @endif

                        @if($gizi->serat_gram >= 4)
                            <span class="tag">🌿 Tinggi serat</span>
                        @endif

                        @if($gizi->karbohidrat_gram <= 50)
                            <span class="tag">📉 Karbo terkontrol</span>
                        @endif
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    <!-- Disclaimer -->
    <div class="disclaimer mt-5">
        ℹ️ <strong>Catatan:</strong> Rekomendasi ini bersifat sebagai pendukung keputusan, bukan pengganti saran medis.
        Selalu konsultasikan perubahan pola makan anak dengan dokter atau ahli gizi.
    </div>

</div>

<footer>SPK Rekomendasi Menu Anak DM Tipe 1 &nbsp;·&nbsp; Metode Fuzzy AHP &nbsp;·&nbsp; AKG Kemenkes 2019</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
