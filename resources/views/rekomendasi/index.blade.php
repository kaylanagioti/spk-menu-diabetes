{{-- resources/views/rekomendasi/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekomendasi Menu Diabetes</title>
</head>
<body>

<h2>Form Data Anak</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form action="{{ route('rekomendasi.proses') }}" method="POST">
    @csrf

    <label>Nama Anak</label><br>
    <input type="text" name="nama" value="{{ old('nama') }}" required><br>
    @error('nama') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin" required>
        <option value="">-- Pilih --</option>
        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
    </select><br>
    @error('jenis_kelamin') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Tanggal Lahir</label><br>
    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required><br>
    @error('tanggal_lahir') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Berat Badan (kg)</label><br>
    <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" required><br>
    @error('berat_badan') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Tinggi Badan (cm)</label><br>
    <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required><br>
    @error('tinggi_badan') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Tingkat Aktivitas</label><br>
    <select name="tingkat_aktivitas" required>
        <option value="">-- Pilih --</option>
        <option value="ringan" {{ old('tingkat_aktivitas') == 'ringan' ? 'selected' : '' }}>Ringan</option>
        <option value="sedang" {{ old('tingkat_aktivitas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="berat"  {{ old('tingkat_aktivitas') == 'berat'  ? 'selected' : '' }}>Berat</option>
    </select><br>
    @error('tingkat_aktivitas') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <label>Waktu Makan</label><br>
    <select name="waktu_makan" required>
        <option value="">-- Pilih --</option>
        <option value="sarapan"     {{ old('waktu_makan') == 'sarapan'     ? 'selected' : '' }}>Sarapan</option>
        <option value="makan_siang" {{ old('waktu_makan') == 'makan_siang' ? 'selected' : '' }}>Makan Siang</option>
        <option value="makan_malam" {{ old('waktu_makan') == 'makan_malam' ? 'selected' : '' }}>Makan Malam</option>
    </select><br>
    @error('waktu_makan') <small style="color:red">{{ $message }}</small><br> @enderror

    <br>
    <button type="submit">Dapatkan Rekomendasi</button>
</form>

</body>
</html>
