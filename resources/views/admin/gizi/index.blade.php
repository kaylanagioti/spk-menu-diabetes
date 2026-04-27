{{-- resources/views/admin/gizi/index.blade.php --}}
@extends('admin.layout')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
    <h2>Kandungan Gizi Menu</h2>
</div>

@if(session('success'))
<div class="alert-success" style="margin-bottom:16px; padding:10px 14px; background:#e8f5ee; border-left:3px solid #4caf80; border-radius:6px; font-size:0.9rem; color:#1a4731">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Waktu Makan</th>
                <th>Energi (kkal)</th>
                <th>Karbo (g)</th>
                <th>Protein (g)</th>
                <th>Lemak (g)</th>
                <th>Serat (g)</th>
                <th>IG</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            @php $gizi = $menu->kandunganGizi; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $menu->jenis_menu)) }}</td>
                @if($gizi)
                    <td>{{ $gizi->energi_kkal }}</td>
                    <td>{{ $gizi->karbohidrat_gram }}</td>
                    <td>{{ $gizi->protein_gram }}</td>
                    <td>{{ $gizi->lemak_gram }}</td>
                    <td>{{ $gizi->serat_gram }}</td>
                    <td>{{ $gizi->indeks_glikemik ?? '-' }}</td>
                @else
                    <td colspan="6" style="color:#f59e0b">⚠️ Belum diisi</td>
                @endif
                <td>
                    <a class="btn btn-blue" href="{{ route('admin.gizi.edit', [$menu, 'key' => request('key')]) }}">
                        {{ $gizi ? 'Edit' : '+ Isi Gizi' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="color:#999">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
