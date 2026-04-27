{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')

<h2 style="margin-bottom:20px">Dashboard</h2>

<div style="display:flex; gap:16px; margin-bottom:24px">
    <div class="card" style="flex:1; text-align:center">
        <div style="font-size:36px; font-weight:bold; color:#2c7a2c">{{ $totalMenu }}</div>
        <div style="color:#666; margin-top:4px">Total Menu</div>
    </div>
    <div class="card" style="flex:1; text-align:center">
        <div style="font-size:36px; font-weight:bold; color:#1a56db">{{ $totalRekomendasi }}</div>
        <div style="color:#666; margin-top:4px">Total Rekomendasi</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom:12px">Akses Cepat</h3>
    <a class="btn btn-green" href="{{ route('admin.menu.create', ['key' => request('key')]) }}">+ Tambah Menu</a>
    &nbsp;
    <a class="btn btn-blue" href="{{ route('admin.debug', ['key' => request('key')]) }}">Lihat Debug / CR</a>
</div>

@endsection
