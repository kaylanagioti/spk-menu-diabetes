{{-- resources/views/admin/debug.blade.php --}}
@extends('admin.layout')

@section('content')

<h2 style="margin-bottom:20px">Debug — Detail Fuzzy AHP</h2>

{{-- Consistency Ratio --}}
<div class="card">
    <h3>Consistency Ratio (CR)</h3>
    <p style="margin:12px 0; font-size:28px; font-weight:bold; color:{{ $crValid ? '#2c7a2c' : '#c0392b' }}">
        {{ $cr }}
        @if($crValid)
            <span style="font-size:14px; color:#2c7a2c">✅ Valid (CR &lt; 0.1)</span>
        @else
            <span style="font-size:14px; color:#c0392b">⚠️ Tidak valid (CR ≥ 0.1)</span>
        @endif
    </p>
    <p style="font-size:13px; color:#666">Syarat konsistensi AHP: CR &lt; 0.1 (Saaty, 1980)</p>
</div>

{{-- Bobot Kriteria --}}
<div class="card">
    <h3 style="margin-bottom:12px">Bobot Kriteria (Hasil Fuzzy AHP)</h3>
    <table>
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Tipe</th>
                <th>Bobot</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tipe = [
                    'kalori'          => 'Target',
                    'karbohidrat'     => 'Cost',
                    'protein'         => 'Benefit',
                    'serat'           => 'Benefit',
                    'indeks_glikemik' => 'Cost',
                ];
            @endphp
            @foreach($bobot as $kriteria => $nilai)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $kriteria)) }}</td>
                <td>{{ $tipe[$kriteria] ?? '-' }}</td>
                <td>{{ number_format($nilai, 6) }}</td>
                <td>{{ number_format($nilai * 100, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Riwayat Ranking --}}
<div class="card">
    <h3 style="margin-bottom:12px">Riwayat Rekomendasi Terakhir (20 data)</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Anak</th>
                <th>Waktu Makan</th>
                <th>Menu</th>
                <th>Ranking</th>
                <th>Skor</th>
                <th>CR</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
            <tr>
                <td>{{ $item->tanggal_rekomendasi->format('d/m/Y') }}</td>
                <td>{{ $item->anak->nama ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->waktu_makan)) }}</td>
                <td>{{ $item->menu->nama_menu ?? '-' }}</td>
                <td align="center">{{ $item->ranking }}</td>
                <td>{{ number_format($item->nilai_preferensi, 4) }}</td>
                <td style="color:{{ $item->consistency_ratio < 0.1 ? 'green' : 'red' }}">
                    {{ $item->consistency_ratio }}
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="color:#999">Belum ada data rekomendasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
