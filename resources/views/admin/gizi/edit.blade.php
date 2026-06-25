{{-- resources/views/admin/gizi/edit.blade.php --}}
@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:6px">Edit Kandungan Gizi</h2>
<p style="color:#666; margin-bottom:20px; font-size:0.9rem">{{ $menu->nama_menu }}</p>

<div class="card">
    <p style="font-size:12px;color:#666;margin-bottom:16px">
        Kriteria yang digunakan dalam algoritma Fuzzy AHP:
        <strong>Kalori, Karbohidrat, Protein, Serat</strong>.
        Data ini langsung memengaruhi perhitungan rekomendasi.
    </p>

    <form action="{{ route('admin.gizi.update', $menu) }}" method="POST">
        @csrf @method('PUT')

        <div class="form-row">
            <div>
                <label>Kalori / Energi (kkal)</label>
                <input type="number" step="0.01" name="energi_kkal"
                       value="{{ old('energi_kkal', $gizi->energi_kkal) }}" required>
                @error('energi_kkal') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Karbohidrat (gram)</label>
                <input type="number" step="0.01" name="karbohidrat_gram"
                       value="{{ old('karbohidrat_gram', $gizi->karbohidrat_gram) }}" required>
                @error('karbohidrat_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label>Protein (gram)</label>
                <input type="number" step="0.01" name="protein_gram"
                       value="{{ old('protein_gram', $gizi->protein_gram) }}" required>
                @error('protein_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Serat (gram)</label>
                <input type="number" step="0.01" name="serat_gram"
                       value="{{ old('serat_gram', $gizi->serat_gram) }}" required>
                @error('serat_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <label>Sumber Data <span style="font-weight:normal;color:#888">(opsional)</span></label>
        <input type="text" name="sumber_data"
               value="{{ old('sumber_data', $gizi->sumber_data) }}"
               placeholder="Contoh: TKPI 2020, USDA">
        @error('sumber_data') <div class="error-msg">{{ $message }}</div> @enderror

        <a class="btn btn-gray" href="{{ route('admin.gizi.index') }}">Batal</a>
        &nbsp;
        <button class="btn btn-green" type="submit">Simpan Gizi</button>
    </form>
</div>
@endsection
