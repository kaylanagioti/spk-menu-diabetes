<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KandunganGizi;
use App\Models\Menu;
use Illuminate\Http\Request;

class KandunganGiziController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kandunganGizi')->orderBy('jenis_menu')->get();
        return view('admin.gizi.index', compact('menus'));
    }

    public function edit(Menu $menu)
    {
        $gizi = $menu->kandunganGizi ?? new KandunganGizi(['menu_id' => $menu->id]);
        return view('admin.gizi.edit', compact('menu', 'gizi'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'energi_kkal'      => ['required', 'numeric', 'min:0'],
            'karbohidrat_gram' => ['required', 'numeric', 'min:0'],
            'protein_gram'     => ['required', 'numeric', 'min:0'],
            'lemak_gram'       => ['required', 'numeric', 'min:0'],
            'serat_gram'       => ['required', 'numeric', 'min:0'],
            'indeks_glikemik'  => ['nullable', 'integer', 'min:0', 'max:100'],
            'gula_gram'        => ['nullable', 'numeric', 'min:0'],
            'sumber_data'      => ['nullable', 'string', 'max:100'],
        ]);

        $menu->kandunganGizi()->updateOrCreate(
            ['menu_id' => $menu->id],
            $request->only([
                'energi_kkal', 'karbohidrat_gram', 'protein_gram',
                'lemak_gram', 'serat_gram', 'indeks_glikemik',
                'gula_gram', 'sumber_data',
            ])
        );

        return redirect()
            ->route('admin.gizi.index', ['key' => request('key')])
            ->with('success', 'Data gizi berhasil diperbarui.');
    }
}
