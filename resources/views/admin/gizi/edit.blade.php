{{-- resources/views/admin/gizi/edit.blade.php --}}
@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:6px">Kandungan Gizi</h2>
<p style="color:#666; margin-bottom:20px; font-size:0.9rem">{{ $menu->nama_menu }}</p>

<div class="card">
    <form action="{{ route('admin.gizi.update', [$menu, 'key' => request('key')]) }}" method="POST">
        @csrf @method('PUT')

        <label>Energi (kkal)</label>
        <input type="number" step="0.01" name="energi_kkal"
               value="{{ old('energi_kkal', $gizi->energi_kkal) }}" required>
        @error('energi_kkal') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Karbohidrat (gram)</label>
        <input type="number" step="0.01" name="karbohidrat_gram"
               value="{{ old('karbohidrat_gram', $gizi->karbohidrat_gram) }}" required>
        @error('karbohidrat_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Protein (gram)</label>
        <input type="number" step="0.01" name="protein_gram"
               value="{{ old('protein_gram', $gizi->protein_gram) }}" required>
        @error('protein_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Lemak (gram)</label>
        <input type="number" step="0.01" name="lemak_gram"
               value="{{ old('lemak_gram', $gizi->lemak_gram) }}" required>
        @error('lemak_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Serat (gram)</label>
        <input type="number" step="0.01" name="serat_gram"
               value="{{ old('serat_gram', $gizi->serat_gram) }}" required>
        @error('serat_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Indeks Glikemik <small style="color:#999">(0–100, opsional)</small></label>
        <input type="number" name="indeks_glikemik" min="0" max="100"
               value="{{ old('indeks_glikemik', $gizi->indeks_glikemik) }}">
        @error('indeks_glikemik') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Gula (gram, opsional)</label>
        <input type="number" step="0.01" name="gula_gram"
               value="{{ old('gula_gram', $gizi->gula_gram) }}">
        @error('gula_gram') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Sumber Data <small style="color:#999">(opsional)</small></label>
        <input type="text" name="sumber_data"
               value="{{ old('sumber_data', $gizi->sumber_data) }}"
               placeholder="Contoh: TKPI 2019, USDA">
        @error('sumber_data') <div class="error-msg">{{ $message }}</div> @enderror

        <a class="btn btn-gray" href="{{ route('admin.gizi.index', ['key' => request('key')]) }}">Batal</a>
        &nbsp;
        <button class="btn btn-green" type="submit">Simpan Gizi</button>
    </form>
</div>
@endsection
