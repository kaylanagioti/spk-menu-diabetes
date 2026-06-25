{{-- resources/views/admin/gizi/index.blade.php --}}
@extends('admin.layout')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
    <h2>Kandungan Gizi Menu</h2>
    <a class="btn btn-green" href="{{ route('admin.menu.index') }}">← Kelola Menu</a>
</div>

<p style="font-size:13px;color:#666;margin-bottom:16px">
    Halaman ini menampilkan ringkasan gizi. Untuk mengubah gizi, gunakan tombol Edit atau
    langsung edit melalui halaman <a href="{{ route('admin.menu.index') }}">Kelola Menu</a>.
    Kriteria aktif dalam Fuzzy AHP: <strong>Kalori, Karbohidrat, Protein, Serat</strong>.
</p>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Waktu Makan</th>
                <th>Energi (kkal)</th>
                <th>Karbohidrat (g)</th>
                <th>Protein (g)</th>
                <th>Serat (g)</th>
                <th>Sumber Data</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            @php $gizi = $menu->kandunganGizi; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $menu->jenis_menu)) }}</td>
                @if($gizi)
                    <td>{{ $gizi->energi_kkal }}</td>
                    <td>{{ $gizi->karbohidrat_gram }}</td>
                    <td>{{ $gizi->protein_gram }}</td>
                    <td>{{ $gizi->serat_gram }}</td>
                    <td style="font-size:12px;color:#666">{{ $gizi->sumber_data ?? '-' }}</td>
                @else
                    <td colspan="5" style="color:#f59e0b">⚠️ Belum diisi</td>
                @endif
                <td>
                    <a class="btn btn-blue" href="{{ route('admin.gizi.edit', $menu) }}">
                        {{ $gizi ? 'Edit Gizi' : '+ Isi Gizi' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="color:#999;text-align:center;padding:20px">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
