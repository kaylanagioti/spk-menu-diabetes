<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemilihanMenu;

class RiwayatPemilihanController extends Controller
{
    public function index()
    {
        $riwayat = PemilihanMenu::with([
            'anak',
            'rekomendasi.menuSarapan',
            'rekomendasi.menuSnackPagi',
            'rekomendasi.menuMakanSiang',
            'rekomendasi.menuSnackSore',
            'rekomendasi.menuMakanMalam',
            'rekomendasi.menuSnackMalam',
        ])
        ->latest('dipilih_pada')
        ->get();

        return view('admin.riwayat.index', compact('riwayat'));
    }
}