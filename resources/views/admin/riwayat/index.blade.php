<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori Pemilihan Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Histori Pemilihan Menu</h2>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-success">
            <tr>
                <th>Anak</th>
                <th>Tanggal</th>
                <th>Ranking</th>
                <th>Sarapan</th>
                <th>Snack Pagi</th>
                <th>Makan Siang</th>
                <th>Snack Sore</th>
                <th>Makan Malam</th>
                <th>Snack Malam</th>
            </tr>
            </thead>

            <tbody>

            @forelse($riwayat as $item)

                <tr>

                    <td>
                        {{ $item->anak->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $item->dipilih_pada?->format('d-m-Y H:i') }}
                    </td>

                    <td>
                        Peringkat {{ $item->ranking_dipilih }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuSarapan->nama_menu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuSnackPagi->nama_menu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuMakanSiang->nama_menu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuSnackSore->nama_menu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuMakanMalam->nama_menu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->rekomendasi->menuSnackMalam->nama_menu ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" class="text-center">
                        Belum ada histori pemilihan.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>