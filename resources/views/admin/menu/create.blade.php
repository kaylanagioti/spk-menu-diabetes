{{-- resources/views/admin/menu/create.blade.php --}}
@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:20px">Tambah Menu Baru</h2>

<div class="card">
    <form action="{{ route('admin.menu.store') }}" method="POST">
        @csrf

        {{-- ── IDENTITAS MENU ── --}}
        <p class="section-title">Identitas Menu</p>

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" required>
        @error('nama_menu') <div class="error-msg">{{ $message }}</div> @enderror

        <div class="form-row">
            <div>
                <label>Waktu Makan</label>
                <select name="jenis_menu" required>
                    <option value="">-- Pilih --</option>
                    @foreach(['sarapan'=>'Sarapan','snack_pagi'=>'Snack Pagi','makan_siang'=>'Makan Siang','snack_sore'=>'Snack Sore','makan_malam'=>'Makan Malam','snack_malam'=>'Snack Malam'] as $val => $label)
                        <option value="{{ $val }}" {{ old('jenis_menu') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis_menu') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Porsi (gram)</label>
                <input type="number" step="0.1" name="porsi_gram" value="{{ old('porsi_gram') }}" required>
                @error('porsi_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <label>URL Gambar <span style="font-weight:normal;color:#888">(opsional)</span></label>
        <input type="url" name="image_url" value="{{ old('image_url') }}"
               placeholder="https://images.unsplash.com/photo-xxx?w=400">
        @error('image_url') <div class="error-msg">{{ $message }}</div> @enderror

        <label>Deskripsi (opsional)</label>
        <textarea name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>

        <label>Sumber Resep (opsional)</label>
        <input type="text" name="sumber_resep" value="{{ old('sumber_resep') }}" placeholder="Contoh: TKPI 2019">

        <hr class="section-divider">

        {{-- ── KANDUNGAN GIZI ── --}}
        <p class="section-title">Kandungan Gizi (per porsi)</p>
        <p style="font-size:12px;color:#666;margin-bottom:14px">
            Gunakan data dari TKPI 2020 atau sumber gizi terpercaya lainnya.
            Kriteria yang digunakan dalam algoritma Fuzzy AHP: Kalori, Karbohidrat, Protein, Serat.
        </p>

        <div class="form-row">
            <div>
                <label>Kalori / Energi (kkal)</label>
                <input type="number" step="0.01" name="energi_kkal" value="{{ old('energi_kkal') }}" required>
                @error('energi_kkal') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Karbohidrat (gram)</label>
                <input type="number" step="0.01" name="karbohidrat_gram" value="{{ old('karbohidrat_gram') }}" required>
                @error('karbohidrat_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div>
                <label>Protein (gram)</label>
                <input type="number" step="0.01" name="protein_gram" value="{{ old('protein_gram') }}" required>
                @error('protein_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Serat (gram)</label>
                <input type="number" step="0.01" name="serat_gram" value="{{ old('serat_gram') }}" required>
                @error('serat_gram') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <label>Sumber Data Gizi <span style="font-weight:normal;color:#888">(opsional)</span></label>
        <input type="text" name="sumber_data" value="{{ old('sumber_data') }}" placeholder="Contoh: TKPI 2020, USDA">

        <hr class="section-divider">

        <a class="btn btn-gray" href="{{ route('admin.menu.index') }}">Batal</a>
        &nbsp;
        <button class="btn btn-green" type="submit">Simpan Menu</button>
    </form>
</div>
@endsection
