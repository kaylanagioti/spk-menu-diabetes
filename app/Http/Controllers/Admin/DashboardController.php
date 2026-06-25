<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Menu;
use App\Models\PemilihanMenu;
use App\Models\Rekomendasi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalAnak'        => Anak::count(),
            'totalMenu'        => Menu::count(),
            'totalRekomendasi' => Rekomendasi::count(),
            'totalPemilihan'   => PemilihanMenu::count(),
        ]);
    }
}
