{{-- resources/views/admin/menu/edit.blade.php --}}
@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:20px">Edit Menu</h2>

<div class="card">
    <form action="{{ route('admin.menu.update', [$menu, 'key' => request('key')]) }}" method="POST">
        @csrf @method('PUT')

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
        @error('nama_menu') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Waktu Makan</label>
        <select name="jenis_menu" required>
            <option value="sarapan"     {{ old('jenis_menu', $menu->jenis_menu) == 'sarapan'     ? 'selected' : '' }}>Sarapan</option>
            <option value="makan_siang" {{ old('jenis_menu', $menu->jenis_menu) == 'makan_siang' ? 'selected' : '' }}>Makan Siang</option>
            <option value="makan_malam" {{ old('jenis_menu', $menu->jenis_menu) == 'makan_malam' ? 'selected' : '' }}>Makan Malam</option>
        </select>

        <label>Porsi (gram)</label>
        <input type="number" step="0.1" name="porsi_gram" value="{{ old('porsi_gram', $menu->porsi_gram) }}" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>

        <label>Sumber Resep</label>
        <input type="text" name="sumber_resep" value="{{ old('sumber_resep', $menu->sumber_resep) }}">

        <label>Status</label>
        <select name="is_active">
            <option value="1" {{ old('is_active', $menu->is_active) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !old('is_active', $menu->is_active) ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <a class="btn btn-gray" href="{{ route('admin.menu.index', ['key' => request('key')]) }}">Batal</a>
        &nbsp;
        <button class="btn btn-green" type="submit">Update</button>
    </form>
</div>
@endsection
