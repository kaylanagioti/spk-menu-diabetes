{{-- resources/views/admin/menu/index.blade.php --}}
@extends('admin.layout')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
    <h2>Kelola Menu</h2>
    <a class="btn btn-green" href="{{ route('admin.menu.create') }}">+ Tambah Menu</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Waktu Makan</th>
                <th>Porsi (g)</th>
                <th>Energi (kkal)</th>
                <th>Karbo (g)</th>
                <th>Protein (g)</th>
                <th>Serat (g)</th>
                <th>Sumber</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            @php $gizi = $menu->kandunganGizi; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $menu->jenis_menu)) }}</td>
                <td>{{ $menu->porsi_gram }}</td>
                @if($gizi)
                    <td>{{ $gizi->energi_kkal }}</td>
                    <td>{{ $gizi->karbohidrat_gram }}</td>
                    <td>{{ $gizi->protein_gram }}</td>
                    <td>{{ $gizi->serat_gram }}</td>
                    <td style="font-size:12px;color:#666">{{ $gizi->sumber_data ?? '-' }}</td>
                @else
                    <td colspan="5" style="color:#f59e0b">⚠️ Belum ada data gizi</td>
                @endif
                <td>
                    <span style="font-size:12px;color:{{ $menu->is_active ? '#2c7a2c' : '#888' }}">
                        {{ $menu->is_active ? '✓ Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="white-space:nowrap">
                    <a class="btn btn-blue" href="{{ route('admin.menu.edit', $menu) }}">Edit</a>
                    &nbsp;
                    <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST"
                          style="display:inline"
                          onsubmit="return confirm('Hapus menu ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-red" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="11" style="color:#999;text-align:center;padding:20px">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
