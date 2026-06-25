<?php

namespace App\Http\Controllers;

use App\Models\PemilihanMenu;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;

class PemilihanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rekomendasi_id'  => 'required|exists:rekomendasi,id',
            'anak_id'         => 'required|exists:anak,id',
            'ranking_dipilih' => 'required|integer|min:1|max:3',
        ]);

        PemilihanMenu::create([
            'rekomendasi_id'  => $request->rekomendasi_id,
            'anak_id'         => $request->anak_id,
            'ranking_dipilih' => $request->ranking_dipilih,
            'dipilih_pada'    => now(),
        ]);

        return redirect()
            ->route('rekomendasi.hasil', $request->anak_id)
            ->with('sukses_pilih', 'Paket Peringkat ' . $request->ranking_dipilih . ' berhasil dipilih.');
    }
}
