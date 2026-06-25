<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KandunganGizi;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kandunganGizi')->orderBy('jenis_menu')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'        => ['required', 'string', 'max:150', 'unique:menu'],
            'jenis_menu'       => ['required', 'in:sarapan,snack_pagi,makan_siang,snack_sore,makan_malam,snack_malam'],
            'porsi_gram'       => ['required', 'numeric', 'min:1'],
            'deskripsi'        => ['nullable', 'string'],
            'sumber_resep'     => ['nullable', 'string', 'max:255'],
            'image_url'        => ['nullable', 'url'],
            // Kandungan gizi
            'energi_kkal'      => ['required', 'numeric', 'min:0'],
            'karbohidrat_gram' => ['required', 'numeric', 'min:0'],
            'protein_gram'     => ['required', 'numeric', 'min:0'],
            'serat_gram'       => ['required', 'numeric', 'min:0'],
            'sumber_data'      => ['nullable', 'string', 'max:100'],
        ]);

        $menu = Menu::create($request->only([
            'nama_menu', 'jenis_menu', 'porsi_gram',
            'deskripsi', 'sumber_resep', 'image_url',
        ]) + ['is_active' => true]);

        // Simpan kandungan gizi sekaligus
        $menu->kandunganGizi()->create([
            'energi_kkal'      => $request->energi_kkal,
            'karbohidrat_gram' => $request->karbohidrat_gram,
            'protein_gram'     => $request->protein_gram,
            'lemak_gram'       => 0,   // kolom wajib di DB, diisi 0 (tidak digunakan algoritma)
            'serat_gram'       => $request->serat_gram,
            'sumber_data'      => $request->sumber_data,
        ]);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu dan kandungan gizi berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $menu->load('kandunganGizi');
        $gizi = $menu->kandunganGizi ?? new KandunganGizi(['menu_id' => $menu->id]);
        return view('admin.menu.edit', compact('menu', 'gizi'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'        => ['required', 'string', 'max:150', 'unique:menu,nama_menu,' . $menu->id],
            'jenis_menu'       => ['required', 'in:sarapan,snack_pagi,makan_siang,snack_sore,makan_malam,snack_malam'],
            'porsi_gram'       => ['required', 'numeric', 'min:1'],
            'image_url'        => ['nullable', 'url'],
            // Kandungan gizi
            'energi_kkal'      => ['required', 'numeric', 'min:0'],
            'karbohidrat_gram' => ['required', 'numeric', 'min:0'],
            'protein_gram'     => ['required', 'numeric', 'min:0'],
            'serat_gram'       => ['required', 'numeric', 'min:0'],
            'sumber_data'      => ['nullable', 'string', 'max:100'],
        ]);

        $menu->update($request->only([
            'nama_menu', 'jenis_menu', 'porsi_gram',
            'deskripsi', 'sumber_resep', 'is_active', 'image_url',
        ]));

        // Update atau buat kandungan gizi
        $menu->kandunganGizi()->updateOrCreate(
            ['menu_id' => $menu->id],
            [
                'energi_kkal'      => $request->energi_kkal,
                'karbohidrat_gram' => $request->karbohidrat_gram,
                'protein_gram'     => $request->protein_gram,
                'lemak_gram'       => $menu->kandunganGizi?->lemak_gram ?? 0,
                'serat_gram'       => $request->serat_gram,
                'sumber_data'      => $request->sumber_data,
            ]
        );

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
