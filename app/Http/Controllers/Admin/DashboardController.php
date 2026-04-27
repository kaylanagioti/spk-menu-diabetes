<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Rekomendasi;
use App\Services\FuzzyAhpService;

class DashboardController extends Controller
{
    public function __construct(private FuzzyAhpService $fuzzyAhp) {}

    public function index()
    {
        return view('admin.dashboard', [
            'totalMenu'        => Menu::count(),
            'totalRekomendasi' => Rekomendasi::count(),
        ]);
    }

    /**
     * Debug view — tampilkan detail teknis Fuzzy AHP.
     * Hanya untuk keperluan validasi akademis & sidang.
     */
    public function debug()
    {
        $cr     = $this->fuzzyAhp->getConsistencyRatio();
        $bobot  = $this->fuzzyAhp->getBobot();

        return view('admin.debug', [
            'cr'       => $cr,
            'crValid'  => $cr < 0.1,
            'bobot'    => $bobot,
            'riwayat'  => Rekomendasi::with(['anak', 'menu'])
                                     ->orderByDesc('created_at')
                                     ->limit(20)
                                     ->get(),
        ]);
    }
}
