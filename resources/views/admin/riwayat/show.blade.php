{{-- resources/views/admin/riwayat/show.blade.php --}}
{{-- Halaman audit trail proses DSS — untuk keperluan sidang skripsi --}}
@extends('admin.layout')

@section('content')

@php
    $labelRanking = ['🏆 Peringkat 1', '🥈 Peringkat 2', '🥉 Peringkat 3'];
    $colorRanking = ['#d4edda', '#d1ecf1', '#fff3cd'];
    $textRanking  = ['#155724', '#0c5460', '#856404'];

    function pctBar(float $pct): string {
        $w = min(100, max(0, $pct * 100));
        return '<div style="background:#e9ecef;border-radius:4px;height:8px;width:100%">
                  <div style="background:#2c7a2c;height:8px;border-radius:4px;width:'.$w.'%"></div>
                </div>';
    }
    $trace = $rekTerpilih->fahp_trace ?? [];

    $raw = $trace['raw'] ?? [];
    $normalized = $trace['normalized'] ?? [];
    $weighted = $trace['weighted'] ?? [];

    $criteria = [
        'kalori' => 'Deviasi Kalori',
        'karbohidrat' => 'Karbohidrat',
        'protein' => 'Protein',
        'serat' => 'Serat',
    ];
@endphp

{{-- ── PAGE HEADER ──────────────────────────────────────────────────── --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px">
    <div>
        <h2 style="margin:0 0 4px">Detail Proses Rekomendasi DSS</h2>
        <p style="margin:0; color:#666; font-size:14px">
            Audit trail proses Sistem Pendukung Keputusan &mdash;
            dibangkitkan pada <strong>{{ $rekTerpilih->tanggal_rekomendasi->format('d F Y') }}</strong>
        </p>
    </div>
    <a href="{{ route('admin.riwayat.index', ['key' => request('key')]) }}"
       class="btn btn-gray">← Kembali ke Riwayat</a>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 1 — RINGKASAN REKOMENDASI                                    --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #2c7a2c; margin-bottom:20px">
    <h3 style="margin:0 0 16px; color:#2c7a2c">1. Ringkasan Rekomendasi</h3>
    <table style="width:auto">
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Tanggal Rekomendasi</td>
            <td style="padding:6px 0; font-weight:bold">
                {{ $rekTerpilih->tanggal_rekomendasi->format('d F Y') }}
            </td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Tanggal Dipilih</td>
            <td style="padding:6px 0; font-weight:bold">
                {{ $pemilihan->dipilih_pada?->format('d F Y, H:i') ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Paket yang Dipilih</td>
            <td style="padding:6px 0">
                @php $r = $pemilihan->ranking_dipilih; @endphp
                <span style="
                    display:inline-block; padding:4px 14px; border-radius:14px; font-weight:bold;
                    background:{{ $colorRanking[$r-1] ?? '#eee' }};
                    color:{{ $textRanking[$r-1] ?? '#333' }};
                ">
                    {{ $labelRanking[$r-1] ?? 'Peringkat '.$r }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Jumlah Paket Dievaluasi</td>
            <td style="padding:6px 0; font-weight:bold">{{ $semuaPaket->count() }} paket</td>
        </tr>
    </table>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 2 — PROFIL ANAK                                               --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #1a56db; margin-bottom:20px">
    <h3 style="margin:0 0 16px; color:#1a56db">2. Profil Anak</h3>
    <table style="width:auto">
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Nama</td>
            <td style="padding:6px 0; font-weight:bold">{{ $anak->nama }}</td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Jenis Kelamin</td>
            <td style="padding:6px 0">{{ $anak->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Tanggal Lahir</td>
            <td style="padding:6px 0">{{ $anak->tanggal_lahir->format('d F Y') }}</td>
        </tr>
        <tr>
            <td style="padding:6px 20px 6px 0; color:#666; font-size:14px">Usia saat Rekomendasi</td>
            <td style="padding:6px 0; font-weight:bold">{{ $anak->usia }} tahun</td>
        </tr>
    </table>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 3 — PERHITUNGAN AKG                                           --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #9673a6; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#9673a6">3. Perhitungan Kebutuhan Energi Harian (AKG)</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Dihitung berdasarkan Angka Kecukupan Gizi (AKG) 2019, PMK No. 28 Tahun 2019,
        menggunakan parameter usia dan jenis kelamin. Nilai AKG digunakan langsung
        tanpa pengali faktor aktivitas (PAL).
    </p>
    <div style="display:flex; gap:16px; flex-wrap:wrap">
        <div style="flex:1; min-width:180px; background:#f3eef7; border-radius:8px; padding:20px; text-align:center">
            <div style="font-size:13px; color:#9673a6; margin-bottom:4px">Usia</div>
            <div style="font-size:28px; font-weight:bold; color:#6a3d8f">{{ $anak->usia }} tahun</div>
        </div>
        <div style="flex:1; min-width:180px; background:#f3eef7; border-radius:8px; padding:20px; text-align:center">
            <div style="font-size:13px; color:#9673a6; margin-bottom:4px">Jenis Kelamin</div>
            <div style="font-size:24px; font-weight:bold; color:#6a3d8f">
                {{ $anak->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
        </div>
        <div style="flex:2; min-width:220px; background:#6a3d8f; border-radius:8px; padding:20px; text-align:center">
            <div style="font-size:13px; color:#ddd; margin-bottom:4px">Kebutuhan Kalori Harian (AKG)</div>
            <div style="font-size:36px; font-weight:bold; color:#fff">
                {{ number_format($totalKalori, 0) }}
                <span style="font-size:18px; font-weight:normal">kkal/hari</span>
            </div>
        </div>
    </div>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 4 — DISTRIBUSI KALORI 6 WAKTU MAKAN                          --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #d79b00; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#b37800">4. Distribusi Kalori ke 6 Waktu Makan</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Distribusi energi harian ke enam waktu makan sesuai rekomendasi ahli gizi
        (validasi pakar): sarapan 20%, snack pagi 10%, makan siang 25%, snack sore 10%,
        makan malam 25%, snack malam 10%.
    </p>
    <table>
        <thead>
            <tr style="background:#fffbe6">
                <th>Waktu Makan</th>
                <th style="text-align:center">Persentase</th>
                <th style="text-align:right">Target Kalori (kkal)</th>
                <th style="width:200px">Proporsi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($distribusi as $waktu => $data)
            <tr>
                <td><strong>{{ $waktu }}</strong></td>
                <td style="text-align:center">{{ $data['persen'] }}%</td>
                <td style="text-align:right; font-weight:bold">{{ number_format($data['kkal'], 0) }}</td>
                <td>
                    <div style="background:#f5f5f5; border-radius:4px; height:10px; width:100%">
                        <div style="background:#d79b00; height:10px; border-radius:4px; width:{{ $data['persen'] * 4 }}%"></div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f9f9f9; font-weight:bold">
                <td>Total</td>
                <td style="text-align:center">100%</td>
                <td style="text-align:right">{{ number_format($totalKalori, 0) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 5 — KRITERIA EVALUASI FUZZY AHP                               --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #c0392b; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#c0392b">5. Kriteria Evaluasi Fuzzy AHP</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Empat kriteria berikut digunakan dalam evaluasi paket menu menggunakan metode
        Fuzzy AHP (Chang, 1996). Seluruh data kandungan gizi bersumber dari TKPI 2020
        (Tabel Komposisi Pangan Indonesia, Kemenkes RI).
    </p>
    <table>
        <thead>
            <tr style="background:#fdecea">
                <th style="width:60px">Kode</th>
                <th>Nama Kriteria</th>
                <th style="text-align:center">Tipe</th>
                <th>Penjelasan</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kriteria as $k)
            <tr>
                <td style="font-weight:bold; color:#c0392b">{{ $k['kode'] }}</td>
                <td><strong>{{ $k['nama'] }}</strong></td>
                <td style="text-align:center">
                    <span style="
                        display:inline-block; padding:2px 10px; border-radius:10px; font-size:12px;
                        background:{{ $k['tipe']==='Benefit' ? '#d4edda' : ($k['tipe']==='Cost' ? '#fdecea' : '#e8f4f8') }};
                        color:{{ $k['tipe']==='Benefit' ? '#155724' : ($k['tipe']==='Cost' ? '#c0392b' : '#0c5460') }};
                    ">{{ $k['tipe'] }}</span>
                </td>
                <td style="font-size:13px; color:#555">
                    @if($k['kode']==='K1')
                        Kesesuaian total kalori paket terhadap kebutuhan energi harian (AKG).
                        Diukur sebagai deviasi absolut — semakin kecil semakin baik.
                    @elseif($k['kode']==='K2')
                        Total karbohidrat paket harian. Sumber utama glukosa darah pada DM Tipe 1;
                        nilai lebih rendah lebih diutamakan.
                    @elseif($k['kode']==='K3')
                        Total protein paket harian. Penting untuk pertumbuhan anak;
                        nilai lebih tinggi lebih diutamakan.
                    @else
                        Total serat paket harian. Memperlambat absorpsi glukosa;
                        nilai lebih tinggi lebih diutamakan.
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 6 — BOBOT FUZZY AHP & CONSISTENCY RATIO                      --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #9673a6; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#9673a6">6. Bobot Kriteria Fuzzy AHP &amp; Consistency Ratio</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Bobot diperoleh melalui metode Chang's Extent Analysis (1996) dengan bilangan fuzzy segitiga (TFN).
        Data ini disimpan sebagai snapshot pada saat proses rekomendasi dijalankan.
    </p>

    {{-- CR Badge --}}
    <div style="margin-bottom:16px">
        <span style="
            display:inline-flex; align-items:center; gap:8px;
            padding:8px 16px; border-radius:8px; font-size:14px;
            background:{{ $cr < 0.1 ? '#d4edda' : '#fdecea' }};
            color:{{ $cr < 0.1 ? '#155724' : '#c0392b' }};
        ">
            <strong>Consistency Ratio (CR) = {{ number_format($cr, 4) }}</strong>
            @if($cr < 0.1)
                &nbsp;✓ Konsisten (CR &lt; 0,10) &mdash; matriks perbandingan dinyatakan valid
            @else
                &nbsp;✗ Tidak konsisten (CR ≥ 0,10)
            @endif
        </span>
    </div>

    <table>
        <thead>
            <tr style="background:#f3eef7">
                <th>Kriteria</th>
                <th style="text-align:right">Bobot (W)</th>
                <th style="text-align:right">Persentase</th>
                <th style="width:220px">Visualisasi Bobot</th>
            </tr>
        </thead>
        <tbody>
        @php
            $urutan = ['karbohidrat','kalori','serat','protein'];
            $namaK  = ['kalori'=>'Kalori','karbohidrat'=>'Karbohidrat','protein'=>'Protein','serat'=>'Serat'];
        @endphp
        @foreach($urutan as $key)
            @if(isset($bobot[$key]))
            @php $w = (float)$bobot[$key]; @endphp
            <tr>
                <td><strong>{{ $namaK[$key] }}</strong></td>
                <td style="text-align:right; font-family:monospace">{{ number_format($w, 6) }}</td>
                <td style="text-align:right; font-weight:bold">{{ number_format($w * 100, 2) }}%</td>
                <td>
                    <div style="background:#e9ecef; border-radius:4px; height:12px; width:100%">
                        <div style="background:#9673a6; height:12px; border-radius:4px;
                                    width:{{ number_format($w * 100, 1) }}%"></div>
                    </div>
                </td>
            </tr>
            @endif
        @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f9f9f9; font-weight:bold">
                <td>Total</td>
                <td style="text-align:right; font-family:monospace">
                    {{ number_format(array_sum(array_values($bobot)), 6) }}
                </td>
                <td style="text-align:right">100,00%</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="accordion mb-4" id="accordionFahp">

    <div class="accordion-item">

        <h2 class="accordion-header">

            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFahp">

                Detail Pembobotan Fuzzy AHP

            </button>

        </h2>

        <div id="collapseFahp"
             class="accordion-collapse collapse"
             data-bs-parent="#accordionFahp">

            <div class="accordion-body">

                {{-- ======================================================= --}}
                {{-- Tahap 1 --}}
                {{-- ======================================================= --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <strong>🔹 Tahap 1. Matriks Perbandingan Berpasangan</strong>

                    </div>

                    <div class="card-body">

                        <p class="text-muted small mb-3">
                            Matriks perbandingan berpasangan disusun menggunakan skala Saaty
                            untuk menentukan tingkat kepentingan relatif antar kriteria.
                        </p>

                        <div class="table-responsive">

                            <table class="table table-bordered table-sm text-center align-middle">

                                <thead class="table-light">

                                <tr>

                                    <th></th>

                                    @foreach($calculationDetails['criteria'] as $criteria)
                                        <th>{{ ucfirst($criteria) }}</th>
                                    @endforeach

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($calculationDetails['pairwise'] as $index => $row)

                                    <tr>

                                        <th class="table-light">

                                            {{ ucfirst($calculationDetails['criteria'][$index]) }}

                                        </th>

                                        @foreach($row as $nilai)

                                            <td>{{ number_format($nilai,2) }}</td>

                                        @endforeach

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- Tahap 2 --}}
                {{-- ======================================================= --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom">

                        <strong>🔹 Tahap 2. Consistency Ratio</strong>

                    </div>

                    <div class="card-body">

                        <p class="text-muted small">

                            Matriks dianggap konsisten apabila nilai Consistency Ratio (CR)
                            kurang dari 0,10.

                        </p>

                        <div class="alert {{ $calculationDetails['consistency_ratio'] < 0.1 ? 'alert-success' : 'alert-danger' }} mb-0">

                            <strong>

                                CR = {{ number_format($calculationDetails['consistency_ratio'],4) }}

                            </strong>

                            <br>

                            @if($calculationDetails['consistency_ratio'] < 0.1)

                                Matriks perbandingan memenuhi syarat konsistensi.

                            @else

                                Matriks belum konsisten.

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ======================================================= --}}
                {{-- Tahap 3 --}}
                {{-- ======================================================= --}}

                <div class="card">

                    <div class="card-header bg-light">

                        <strong>🔹 Tahap 3. Konversi Triangular Fuzzy Number (TFN)</strong>

                    </div>

                    <div class="card-body">

                        <p class="text-muted small">

                            Setiap nilai pada matriks perbandingan dikonversi menjadi
                            Triangular Fuzzy Number (L, M, U).

                        </p>

                        <div class="mb-3">

                            <span class="badge bg-secondary">L = Lower</span>

                            <span class="badge bg-secondary">M = Middle</span>

                            <span class="badge bg-secondary">U = Upper</span>

                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-sm text-center align-middle">

                                <thead class="table-light">

                                <tr>

                                    <th></th>

                                    @foreach($calculationDetails['criteria'] as $criteria)

                                        <th>{{ ucfirst($criteria) }}</th>

                                    @endforeach

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($calculationDetails['fuzzy_matrix'] as $i => $row)

                                    <tr>

                                        <th class="table-light">

                                            {{ ucfirst($calculationDetails['criteria'][$i]) }}

                                        </th>

                                        @foreach($row as $cell)

                                            <td class="font-monospace">

                                                <strong>L</strong> {{ number_format($cell[0],2) }}

                                                <br>

                                                <strong>M</strong> {{ number_format($cell[1],2) }}

                                                <br>

                                                <strong>U</strong> {{ number_format($cell[2],2) }}

                                            </td>

                                        @endforeach

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 7 — JEJAK PERHITUNGAN FUZZY AHP                           --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #6f42c1; margin-bottom:20px">

    <h3 style="margin:0 0 8px; color:#6f42c1">
        7. Jejak Perhitungan Fuzzy AHP
    </h3>

    <p style="color:#666; font-size:13px; margin-bottom:18px">
        Nilai setiap kriteria dinormalisasi menggunakan metode Min-Max sesuai
        jenis kriterianya, kemudian dikalikan dengan bobot Fuzzy AHP untuk
        memperoleh nilai preferensi paket menu.
    </p>

    <table>

        <thead>
        <tr>
            <th></th>

            @foreach($calculationDetails['criteria'] as $nama)
                <th>{{ ucfirst($nama) }}</th>
            @endforeach

        </tr>
        </thead>

        <tbody>

        @foreach($calculationDetails['fuzzy_matrix'] as $i => $row)

        <tr>

            <th>
                {{ ucfirst($calculationDetails['criteria'][$i]) }}
            </th>

            @foreach($row as $cell)

                <td style="font-family:monospace">

                    (
                    {{ number_format($cell[0],2) }},
                    {{ number_format($cell[1],2) }},
                    {{ number_format($cell[2],2) }}
                    )

                </td>

            @endforeach

        </tr>

        @endforeach

        </tbody>

        <tfoot>

        <tr style="background:#faf8fd;font-weight:bold">

            <td colspan="4" style="text-align:right">

                Σ (Bobot × Normalisasi)

            </td>

            <td style="text-align:right;font-family:monospace">

                {{ number_format($trace['score'] ?? 0,6) }}

            </td>

        </tr>

        </tfoot>

    </table>

</div>
<div style="
    margin-top:18px;
    background:#faf8fd;
    border-left:4px solid #6f42c1;
    padding:16px;
    border-radius:6px;
">

    <div style="font-weight:bold;color:#6f42c1;margin-bottom:10px">
        Rumus Perhitungan Nilai Preferensi
    </div>

    <div style="font-family:monospace;font-size:14px">

        Score(P) = Σ (Wi × Xi)

        <br><br>

        =

        ({{ number_format($bobot['kalori'],6) }}
        ×
        {{ number_format($normalized['kalori'],6) }})

        +

        ({{ number_format($bobot['karbohidrat'],6) }}
        ×
        {{ number_format($normalized['karbohidrat'],6) }})

        +

        ({{ number_format($bobot['protein'],6) }}
        ×
        {{ number_format($normalized['protein'],6) }})

        +

        ({{ number_format($bobot['serat'],6) }}
        ×
        {{ number_format($normalized['serat'],6) }})

        <br><br>

        =

        <strong style="font-size:18px;color:#6f42c1">

            {{ number_format($trace['score'],6) }}

        </strong>

    </div>

</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 7 — TIGA PAKET KANDIDAT                                       --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}

<div class="card" style="border-left:4px solid #d79b00; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#b37800">7. Tiga Paket Menu Harian Kandidat</h3>
    <p style="color:#666; font-size:13px; margin:0 0 20px">
        Sistem membentuk tiga paket menu harian sebagai alternatif keputusan.
        Setiap paket terdiri dari enam menu yang dipilih berdasarkan kesesuaian energi
        terhadap target distribusi AKG per waktu makan.
    </p>

    @foreach($semuaPaket as $paket)
    @php
        $rk  = $paket->ranking;
        $bg  = $colorRanking[$rk-1] ?? '#f5f5f5';
        $txt = $textRanking[$rk-1]  ?? '#333';
        $menuSlots = [
            'sarapan'     => ['label'=>'Sarapan',     'rel'=>$paket->menuSarapan],
            'snack_pagi'  => ['label'=>'Snack Pagi',  'rel'=>$paket->menuSnackPagi],
            'makan_siang' => ['label'=>'Makan Siang', 'rel'=>$paket->menuMakanSiang],
            'snack_sore'  => ['label'=>'Snack Sore',  'rel'=>$paket->menuSnackSore],
            'makan_malam' => ['label'=>'Makan Malam', 'rel'=>$paket->menuMakanMalam],
            'snack_malam' => ['label'=>'Snack Malam', 'rel'=>$paket->menuSnackMalam],
        ];
    @endphp

    <div style="border:2px solid {{ $bg }}; border-radius:8px; margin-bottom:16px; overflow:hidden">

        {{-- Header paket --}}
        <div style="background:{{ $bg }}; color:{{ $txt }}; padding:10px 16px;
                    display:flex; justify-content:space-between; align-items:center">
            <strong style="font-size:15px">{{ $labelRanking[$rk-1] ?? 'Peringkat '.$rk }}</strong>
            <span style="font-size:13px">
                Total Kalori Paket:
                <strong>{{ number_format((float)$paket->total_kalori_paket, 0) }} kkal</strong>
            </span>
        </div>

        {{-- Tabel menu per slot --}}
        <div style="padding:0 16px">
            <table style="margin:12px 0">
                <thead>
                    <tr style="background:#fafafa">
                        <th style="width:130px">Waktu Makan</th>
                        <th>Menu</th>
                        <th style="text-align:right">Kalori</th>
                        <th style="text-align:right">Karbo (g)</th>
                        <th style="text-align:right">Protein (g)</th>
                        <th style="text-align:right">Serat (g)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($menuSlots as $slotKey => $slot)
                @php
                    $menu = $slot['rel'];
                    $gizi = $menu?->kandunganGizi;
                @endphp
                <tr>
                    <td style="font-size:13px; color:#666">{{ $slot['label'] }}</td>
                    <td style="font-weight:{{ $menu ? 'normal' : 'normal' }}; font-size:13px">
                        {{ $menu?->nama_menu ?? '<em style="color:#ccc">—</em>' }}
                    </td>
                    <td style="text-align:right; font-size:13px">
                        {{ $gizi ? number_format((float)$gizi->energi_kkal, 0) : '—' }}
                    </td>
                    <td style="text-align:right; font-size:13px">
                        {{ $gizi ? number_format((float)$gizi->karbohidrat_gram, 1) : '—' }}
                    </td>
                    <td style="text-align:right; font-size:13px">
                        {{ $gizi ? number_format((float)$gizi->protein_gram, 1) : '—' }}
                    </td>
                    <td style="text-align:right; font-size:13px">
                        {{ $gizi ? number_format((float)$gizi->serat_gram, 1) : '—' }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endforeach
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 8 — SKOR PREFERENSI & HASIL RANKING                          --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #1a56db; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#1a56db">8. Skor Preferensi &amp; Hasil Perangkingan Fuzzy AHP</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Skor preferensi dihitung menggunakan rumus:
        <strong>Skor(Pₖ) = Σ W(Aⱼ) × x'(k,j)</strong>,
        di mana x'(k,j) adalah nilai gizi ternormalisasi (Min-Max) tiap kriteria.
        Paket dengan skor tertinggi memperoleh Peringkat 1.
    </p>
    <table>
        <thead>
            <tr style="background:#e8f0fe">
                <th>Paket</th>
                <th style="text-align:right">Total Kalori (kkal)</th>
                <th style="text-align:right">Total Karbo (g)</th>
                <th style="text-align:right">Total Protein (g)</th>
                <th style="text-align:right">Total Serat (g)</th>
                <th style="text-align:right">Skor Preferensi</th>
                <th style="text-align:center">Peringkat</th>
            </tr>
        </thead>
        <tbody>
        @foreach($semuaPaket as $paket)
        @php
            $rk  = $paket->ranking;
            $bg  = $colorRanking[$rk-1] ?? '#fff';
            $txt = $textRanking[$rk-1]  ?? '#333';

            // Hitung total gizi dari 6 menu (eager-loaded)
            $totalKarbo   = 0; $totalProtein = 0; $totalSerat = 0;
            foreach ([
                $paket->menuSarapan, $paket->menuSnackPagi, $paket->menuMakanSiang,
                $paket->menuSnackSore, $paket->menuMakanMalam, $paket->menuSnackMalam,
            ] as $m) {
                $g = $m?->kandunganGizi;
                if ($g) {
                    $totalKarbo   += (float)$g->karbohidrat_gram;
                    $totalProtein += (float)$g->protein_gram;
                    $totalSerat   += (float)$g->serat_gram;
                }
            }
        @endphp
        <tr style="background:{{ $rk===1 ? '#f0faf0' : 'transparent' }}">
            <td>
                <span style="display:inline-block; padding:2px 10px; border-radius:10px;
                             background:{{ $bg }}; color:{{ $txt }}; font-size:13px; font-weight:bold">
                    {{ $labelRanking[$rk-1] ?? 'Paket '.$rk }}
                </span>
            </td>
            <td style="text-align:right">{{ number_format((float)$paket->total_kalori_paket, 0) }}</td>
            <td style="text-align:right">{{ number_format($totalKarbo, 1) }}</td>
            <td style="text-align:right">{{ number_format($totalProtein, 1) }}</td>
            <td style="text-align:right">{{ number_format($totalSerat, 1) }}</td>
            <td style="text-align:right; font-family:monospace; font-weight:bold; font-size:14px">
                {{ number_format((float)$paket->nilai_preferensi, 6) }}
            </td>
            <td style="text-align:center">
                <span style="font-size:18px">{{ ['🏆','🥈','🥉'][$rk-1] ?? $rk }}</span>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- SEKSI 9 — PAKET YANG DIPILIH ORANG TUA                             --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<div class="card" style="border-left:4px solid #2c7a2c; margin-bottom:20px">
    <h3 style="margin:0 0 8px; color:#2c7a2c">9. Paket yang Dipilih oleh Orang Tua</h3>
    <p style="color:#666; font-size:13px; margin:0 0 16px">
        Setelah melihat ketiga rekomendasi, orang tua memilih paket berikut sebagai
        rencana menu harian anak.
    </p>

    @php
        $rk   = $pemilihan->ranking_dipilih;
        $bg   = $colorRanking[$rk-1] ?? '#eee';
        $txt  = $textRanking[$rk-1]  ?? '#333';
        $menuSlots = [
            'sarapan'     => ['label'=>'Sarapan',     'rel'=>$rekTerpilih->menuSarapan],
            'snack_pagi'  => ['label'=>'Snack Pagi',  'rel'=>$rekTerpilih->menuSnackPagi],
            'makan_siang' => ['label'=>'Makan Siang', 'rel'=>$rekTerpilih->menuMakanSiang],
            'snack_sore'  => ['label'=>'Snack Sore',  'rel'=>$rekTerpilih->menuSnackSore],
            'makan_malam' => ['label'=>'Makan Malam', 'rel'=>$rekTerpilih->menuMakanMalam],
            'snack_malam' => ['label'=>'Snack Malam', 'rel'=>$rekTerpilih->menuSnackMalam],
        ];
    @endphp

    {{-- Badge pilihan --}}
    <div style="background:{{ $bg }}; border-radius:8px; padding:14px 18px; margin-bottom:16px;
                display:flex; align-items:center; gap:12px">
        <span style="font-size:32px">{{ ['🏆','🥈','🥉'][$rk-1] ?? '📌' }}</span>
        <div>
            <div style="font-weight:bold; font-size:16px; color:{{ $txt }}">
                {{ $labelRanking[$rk-1] ?? 'Peringkat '.$rk }}
            </div>
            <div style="font-size:13px; color:#666; margin-top:2px">
                Dipilih pada {{ $pemilihan->dipilih_pada?->format('d F Y, H:i') ?? '-' }}
                &nbsp;&bull;&nbsp;
                Skor preferensi: <strong>{{ number_format((float)$rekTerpilih->nilai_preferensi, 6) }}</strong>
            </div>
        </div>
    </div>

    {{-- Tabel menu paket terpilih --}}
    <table>
        <thead>
            <tr style="background:#f5faf5">
                <th style="width:130px">Waktu Makan</th>
                <th>Menu yang Direkomendasikan</th>
                <th style="text-align:right">Kalori</th>
                <th style="text-align:right">Karbo (g)</th>
                <th style="text-align:right">Protein (g)</th>
                <th style="text-align:right">Serat (g)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($menuSlots as $slotKey => $slot)
        @php
            $menu = $slot['rel'];
            $gizi = $menu?->kandunganGizi;
        @endphp
        <tr>
            <td style="font-size:13px; color:#666; font-weight:bold">{{ $slot['label'] }}</td>
            <td style="font-size:14px">{{ $menu?->nama_menu ?? '—' }}</td>
            <td style="text-align:right; font-size:13px">
                {{ $gizi ? number_format((float)$gizi->energi_kkal, 0) : '—' }}
            </td>
            <td style="text-align:right; font-size:13px">
                {{ $gizi ? number_format((float)$gizi->karbohidrat_gram, 1) : '—' }}
            </td>
            <td style="text-align:right; font-size:13px">
                {{ $gizi ? number_format((float)$gizi->protein_gram, 1) : '—' }}
            </td>
            <td style="text-align:right; font-size:13px">
                {{ $gizi ? number_format((float)$gizi->serat_gram, 1) : '—' }}
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f0faf0; font-weight:bold">
                <td>Total Paket</td>
                <td></td>
                <td style="text-align:right">{{ number_format((float)$rekTerpilih->total_kalori_paket, 0) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- ── Tombol aksi bawah ──────────────────────────────────────────── --}}
<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px; margin-bottom:40px">
    <a href="{{ route('admin.riwayat.index', ['key' => request('key')]) }}"
       class="btn btn-gray">← Kembali ke Riwayat</a>

    <button onclick="window.print()" class="btn btn-blue">
        🖨️ Cetak / Simpan PDF
    </button>
</div>

@endsection
