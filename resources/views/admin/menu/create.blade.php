{{-- resources/views/admin/menu/create.blade.php --}}
@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:20px">Tambah Menu Baru</h2>

<div class="card">
    <form action="{{ route('admin.menu.store', ['key' => request('key')]) }}" method="POST">
        @csrf

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" required>
        @error('nama_menu') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Waktu Makan</label>
        <select name="jenis_menu" required>
            <option value="">-- Pilih --</option>
            <option value="sarapan"     {{ old('jenis_menu') == 'sarapan'     ? 'selected' : '' }}>Sarapan</option>
            <option value="makan_siang" {{ old('jenis_menu') == 'makan_siang' ? 'selected' : '' }}>Makan Siang</option>
            <option value="makan_malam" {{ old('jenis_menu') == 'makan_malam' ? 'selected' : '' }}>Makan Malam</option>
        </select>
        @error('jenis_menu') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Porsi (gram)</label>
        <input type="number" step="0.1" name="porsi_gram" value="{{ old('porsi_gram') }}" required>
        @error('porsi_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Deskripsi (opsional)</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>

        <label>Sumber Resep (opsional)</label>
        <input type="text" name="sumber_resep" value="{{ old('sumber_resep') }}" placeholder="TKPI 2017">

        <a class="btn btn-gray" href="{{ route('admin.menu.index', ['key' => request('key')]) }}">Batal</a>
        &nbsp;
        <button class="btn btn-green" type="submit">Simpan</button>
    </form>
</div>
@endsection
