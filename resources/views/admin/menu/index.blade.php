{{-- resources/views/admin/menu/index.blade.php --}}
@extends('admin.layout')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
    <h2>Daftar Menu</h2>
    <a class="btn btn-green" href="{{ route('admin.menu.create', ['key' => request('key')]) }}">+ Tambah Menu</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Waktu Makan</th>
                <th>Porsi (gram)</th>
                <th>Gizi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $menu->jenis_menu)) }}</td>
                <td>{{ $menu->porsi_gram }}</td>
                <td>
                    @if($menu->kandunganGizi)
                        <span style="color:green">✅ Ada</span>
                    @else
                        <span style="color:orange">⚠️ Belum diisi</span>
                    @endif
                </td>
                <td>{{ $menu->is_active ? '✅ Aktif' : '❌ Nonaktif' }}</td>
                <td>
                    <a class="btn btn-blue" href="{{ route('admin.menu.edit', [$menu, 'key' => request('key')]) }}">Edit</a>
                    <a class="btn btn-blue" href="{{ route('admin.gizi.edit', [$menu, 'key' => request('key')]) }}">Gizi</a>
                    <form action="{{ route('admin.menu.destroy', [$menu, 'key' => request('key')]) }}" method="POST" style="display:inline"
                          onsubmit="return confirm('Hapus menu ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-red">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="color:#999">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
