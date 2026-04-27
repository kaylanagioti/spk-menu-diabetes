{{-- resources/views/rekomendasi/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiabetMenu — Rekomendasi Menu Anak</title>
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
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
            padding: 60px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -30px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2.6rem;
            color: #fff;
            line-height: 1.2;
        }
        .hero-sub {
            color: rgba(255,255,255,0.75);
            font-size: 1.05rem;
            margin-top: 10px;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            color: #a8dfc0;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        /* ── FORM CARD ── */
        .form-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 40px rgba(26,71,49,0.12);
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }
        .form-card h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--green-dark);
            margin-bottom: 6px;
        }
        .form-card .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 28px;
        }

        /* ── FORM FIELDS ── */
        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid #d8e8df;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.92rem;
            background: #fafcfb;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--green-light);
            box-shadow: 0 0 0 3px rgba(76,175,128,0.12);
            background: #fff;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback { font-size: 0.8rem; }

        /* ── SECTION DIVIDER ── */
        .section-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--green-mid);
            font-weight: 600;
            margin-bottom: 14px;
            margin-top: 24px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--green-pale);
        }

        /* ── WAKTU MAKAN CARDS ── */
        .waktu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 8px;
        }
        .waktu-card input[type=radio] { display: none; }
        .waktu-card label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 14px 10px;
            border: 2px solid #d8e8df;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--muted);
            background: #fafcfb;
        }
        .waktu-card label .icon { font-size: 1.5rem; }
        .waktu-card input:checked + label {
            border-color: var(--green-mid);
            background: var(--green-pale);
            color: var(--green-dark);
        }

        /* ── SUBMIT BTN ── */
        .btn-submit {
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            margin-top: 8px;
            transition: transform 0.15s, box-shadow 0.15s;
            letter-spacing: 0.02em;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45,122,79,0.35);
            color: white;
        }

        /* ── INFO BOX ── */
        .info-note {
            background: var(--green-pale);
            border-left: 3px solid var(--green-light);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.83rem;
            color: var(--muted);
            margin-top: 20px;
        }

        /* ── FOOTER ── */
        footer {
            text-align: center;
            padding: 30px;
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- HERO -->
<div class="hero">
    <div class="container">
        <span class="hero-badge">🩺 Sistem Pendukung Keputusan</span>
        <div class="hero-title">Rekomendasi Menu<br>Anak Diabetes Mellitus</div>
        <p class="hero-sub">Masukkan data anak untuk mendapatkan rekomendasi menu<br>yang sesuai kebutuhan gizi harian.</p>
    </div>
</div>

<!-- FORM -->
<div class="container" style="max-width:680px">
    <div class="form-card">
        <h2>Data Anak</h2>
        <p class="subtitle">Isi informasi di bawah untuk melanjutkan</p>

        @if($errors->any())
        <div class="alert alert-danger rounded-3 py-2 px-3" style="font-size:0.85rem">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('rekomendasi.proses') }}" method="POST">
            @csrf

            <!-- Identitas -->
            <div class="section-label">Identitas Anak</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Anak</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Data Fisik -->
            <div class="section-label">Data Fisik</div>
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan"
                           class="form-control @error('berat_badan') is-invalid @enderror"
                           value="{{ old('berat_badan') }}" placeholder="Contoh: 25.5" required>
                    @error('berat_badan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-6">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan"
                           class="form-control @error('tinggi_badan') is-invalid @enderror"
                           value="{{ old('tinggi_badan') }}" placeholder="Contoh: 120" required>
                    @error('tinggi_badan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Tingkat Aktivitas</label>
                    <select name="tingkat_aktivitas" class="form-select @error('tingkat_aktivitas') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="ringan" {{ old('tingkat_aktivitas') == 'ringan' ? 'selected' : '' }}>Ringan (bermain di dalam rumah)</option>
                        <option value="sedang" {{ old('tingkat_aktivitas') == 'sedang' ? 'selected' : '' }}>Sedang (sekolah, bermain aktif)</option>
                        <option value="berat"  {{ old('tingkat_aktivitas') == 'berat'  ? 'selected' : '' }}>Berat (olahraga rutin)</option>
                    </select>
                    @error('tingkat_aktivitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Waktu Makan -->
            <div class="section-label">Waktu Makan</div>
            <div class="waktu-grid">
                <div class="waktu-card">
                    <input type="radio" name="waktu_makan" id="sarapan" value="sarapan"
                           {{ old('waktu_makan') == 'sarapan' ? 'checked' : '' }}>
                    <label for="sarapan">
                        <span class="icon">🌅</span> Sarapan
                    </label>
                </div>
                <div class="waktu-card">
                    <input type="radio" name="waktu_makan" id="makan_siang" value="makan_siang"
                           {{ old('waktu_makan') == 'makan_siang' ? 'checked' : '' }}>
                    <label for="makan_siang">
                        <span class="icon">☀️</span> Makan Siang
                    </label>
                </div>
                <div class="waktu-card">
                    <input type="radio" name="waktu_makan" id="makan_malam" value="makan_malam"
                           {{ old('waktu_makan') == 'makan_malam' ? 'checked' : '' }}>
                    <label for="makan_malam">
                        <span class="icon">🌙</span> Makan Malam
                    </label>
                </div>
            </div>
            @error('waktu_makan')
                <div style="color:#dc3545; font-size:0.8rem; margin-top:4px">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-submit mt-4">
                🍽️ Dapatkan Rekomendasi Menu
            </button>
        </form>

        <div class="info-note">
            ℹ️ Data yang Anda masukkan digunakan untuk menghitung kebutuhan kalori menggunakan
            standar AKG Kemenkes 2019 dan diproses melalui metode Fuzzy AHP.
        </div>
    </div>
</div>

<footer>
    SPK Rekomendasi Menu Anak DM Tipe 1 &nbsp;·&nbsp; Menggunakan Metode Fuzzy AHP
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
