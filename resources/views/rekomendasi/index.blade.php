<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Menu Harian Anak Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau-soft: #6BAF92;
            --hijau-tua: #3D7A5D;
            --krem: #F5F8F4;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--krem); color: #2c3e2d; }
        h1, h2, h3 { font-family: 'DM Serif Display', serif; }
        .hero { background: linear-gradient(135deg, var(--hijau-soft), var(--hijau-tua)); color: #fff; padding: 48px 0; border-radius: 0 0 28px 28px; }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,.07); }
        .btn-hijau { background: var(--hijau-tua); color: #fff; border-radius: 12px; padding: 12px; font-weight: 600; }
        .btn-hijau:hover { background: var(--hijau-soft); color: #fff; }
        .form-label { font-weight: 500; }
    </style>
</head>
<body>

<div class="hero text-center mb-4">
    <div class="container">
        <h1 class="mb-2">Rekomendasi Menu Harian</h1>
        <p class="lead mb-0">Rencana makan sehari penuh untuk anak dengan Diabetes Mellitus Tipe 1</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card card-form p-4 p-md-5">
                <h3 class="mb-1">Data Anak</h3>
                <p class="text-muted mb-4" style="font-size:.95rem">
                    Isi data anak di bawah. Sistem akan otomatis menyusun rencana menu
                    untuk <strong>sarapan, snack pagi, makan siang, snack sore, makan malam,</strong>
                    dan <strong>snack malam</strong>.
                </p>

                <form action="{{ route('rekomendasi.proses') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Anak</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-hijau w-100">
                        Buat Rencana Menu Harian
                    </button>
                </form>
            </div>

            <p class="text-center text-muted mt-3" style="font-size:.85rem">
                Rekomendasi ini bersifat bantuan/edukasi. Konsultasikan tetap dengan ahli gizi atau dokter.
            </p>

        </div>
    </div>
</div>

</body>
</html>
