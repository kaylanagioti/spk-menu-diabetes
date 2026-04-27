<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'nama_menu'    => ['required', 'string', 'max:150', 'unique:menu'],
            'jenis_menu'   => ['required', 'in:sarapan,makan_siang,makan_malam'],
            'porsi_gram'   => ['required', 'numeric', 'min:1'],
            'deskripsi'    => ['nullable', 'string'],
            'sumber_resep' => ['nullable', 'string', 'max:255'],
        ]);

        Menu::create($request->only([
            'nama_menu', 'jenis_menu', 'porsi_gram',
            'deskripsi', 'sumber_resep',
        ]) + ['is_active' => true]);

        return redirect()
            ->route('admin.menu.index', ['key' => request('key')])
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'  => ['required', 'string', 'max:150', 'unique:menu,nama_menu,' . $menu->id],
            'jenis_menu' => ['required', 'in:sarapan,makan_siang,makan_malam'],
            'porsi_gram' => ['required', 'numeric', 'min:1'],
        ]);

        $menu->update($request->only([
            'nama_menu', 'jenis_menu', 'porsi_gram',
            'deskripsi', 'sumber_resep', 'is_active',
        ]));

        return redirect()
            ->route('admin.menu.index', ['key' => request('key')])
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('admin.menu.index', ['key' => request('key')])
            ->with('success', 'Menu berhasil dihapus.');
    }
}
