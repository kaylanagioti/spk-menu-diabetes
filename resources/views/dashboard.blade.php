{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')

<h2 style="margin-bottom:20px">Dashboard</h2>

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px">
    <div class="card" style="text-align:center">
        <div style="font-size:34px; font-weight:bold; color:#1a4a1a">{{ $totalAnak }}</div>
        <div style="color:#666; margin-top:4px; font-size:13px">👦 Total Anak</div>
    </div>
    <div class="card" style="text-align:center">
        <div style="font-size:34px; font-weight:bold; color:#2c7a2c">{{ $totalMenu }}</div>
        <div style="color:#666; margin-top:4px; font-size:13px">🍽️ Total Menu</div>
    </div>
    <div class="card" style="text-align:center">
        <div style="font-size:34px; font-weight:bold; color:#1a56db">{{ $totalRekomendasi }}</div>
        <div style="color:#666; margin-top:4px; font-size:13px">📊 Total Rekomendasi</div>
    </div>
    <div class="card" style="text-align:center">
        <div style="font-size:34px; font-weight:bold; color:#7c3aed">{{ $totalPemilihan }}</div>
        <div style="color:#666; margin-top:4px; font-size:13px">✅ Total Pemilihan</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom:14px">Akses Cepat</h3>
    <a class="btn btn-green" href="{{ route('admin.menu.create') }}">+ Tambah Menu Baru</a>
    &nbsp;
    <a class="btn btn-blue" href="{{ route('admin.riwayat.index') }}">Lihat Riwayat Pemilihan</a>
    &nbsp;
    <a class="btn btn-gray" href="{{ route('admin.gizi.index') }}">Kelola Kandungan Gizi</a>
</div>

@endsection
