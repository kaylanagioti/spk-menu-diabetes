{{-- resources/views/admin/riwayat/index.blade.php --}}
@extends('admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4"
     style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
    <h2 style="margin:0">Riwayat Pemilihan Menu</h2>
    <a href="{{ route('admin.dashboard', ['key' => request('key')]) }}"
       class="btn btn-gray">← Kembali</a>
</div>

@if($riwayat->isEmpty())
    <div class="card" style="text-align:center; color:#888; padding:40px">
        Belum ada riwayat pemilihan menu.
    </div>
@else
<div class="card" style="padding:0; overflow:hidden">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Nama Anak</th>
                    <th>Tanggal Pemilihan</th>
                    <th>Peringkat Dipilih</th>
                    <th>Sarapan</th>
                    <th>Makan Siang</th>
                    <th>Makan Malam</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($riwayat as $i => $item)
                <tr>
                    <td style="color:#999">{{ $i + 1 }}</td>

                    <td><strong>{{ $item->anak->nama ?? '-' }}</strong></td>

                    <td>{{ $item->dipilih_pada?->format('d/m/Y H:i') ?? '-' }}</td>

                    <td>
                        @php $r = $item->ranking_dipilih; @endphp
                        <span style="
                            display:inline-block; padding:3px 10px; border-radius:12px; font-size:13px; font-weight:bold;
                            background: {{ $r==1 ? '#d4edda' : ($r==2 ? '#d1ecf1' : '#fff3cd') }};
                            color:      {{ $r==1 ? '#155724' : ($r==2 ? '#0c5460' : '#856404') }};
                        ">
                            {{ $r==1 ? '🏆' : ($r==2 ? '🥈' : '🥉') }} Peringkat {{ $r }}
                        </span>
                    </td>

                    <td style="font-size:13px">
                        {{ $item->rekomendasi->menuSarapan->nama_menu ?? '-' }}
                    </td>

                    <td style="font-size:13px">
                        {{ $item->rekomendasi->menuMakanSiang->nama_menu ?? '-' }}
                    </td>

                    <td style="font-size:13px">
                        {{ $item->rekomendasi->menuMakanMalam->nama_menu ?? '-' }}
                    </td>

                    <td style="text-align:center">
                        <a href="{{ route('admin.riwayat.show', [$item->id, 'key' => request('key')]) }}"
                           class="btn btn-blue"
                           style="font-size:12px; padding:5px 12px; white-space:nowrap">
                            🔍 Lihat Proses
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#888; padding:30px">
                        Belum ada histori pemilihan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
