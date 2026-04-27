{{-- resources/views/rekomendasi/riwayat.blade.php --}}
{{-- Riwayat rekomendasi --}}

<x-app-layout>
    <h2>Riwayat Rekomendasi — {{ $anak->nama }}</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu Makan</th>
                <th>Ranking</th>
                <th>Menu</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $item)
                <tr>
                    <td>{{ $item->tanggal_rekomendasi->format('d/m/Y') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->waktu_makan)) }}</td>
                    <td>{{ $item->ranking }}</td>
                    <td>{{ $item->menu->nama_menu }}</td>
                    <td>{{ number_format($item->nilai_preferensi, 4) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada riwayat.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $riwayat->links() }}
</x-app-layout>
