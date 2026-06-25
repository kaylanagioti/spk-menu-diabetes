<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemilihanMenu;
use App\Models\Rekomendasi;
use App\Services\FuzzyAhpService;

class RiwayatPemilihanController extends Controller
{
    /**
     * Daftar semua riwayat pemilihan menu oleh orang tua.
     */
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
    public function __construct(
    private FuzzyAhpService $fuzzyAhpService
    ) {}

    /**
     * Detail proses DSS untuk satu record pemilihan.
     *
     * Semua data bersumber dari snapshot yang disimpan di tabel rekomendasi
     * pada saat proses rekomendasi dijalankan. Tidak ada kalkulasi ulang.
     *
     * Cara menemukan ketiga paket dari satu sesi:
     *   PemilihanMenu → rekomendasi → {anak_id, tanggal_rekomendasi}
     *   → ambil semua Rekomendasi dengan anak_id + tanggal_rekomendasi yang sama
     *   → hasilnya adalah 3 paket (Peringkat 1, 2, 3) dari sesi tersebut.
     */
    public function show(PemilihanMenu $pemilihan)
    {
        // Load relasi pemilihan yang dibutuhkan
        $pemilihan->load(['anak', 'rekomendasi']);

        $anak         = $pemilihan->anak;
        $rekTerpilih  = $pemilihan->rekomendasi;

        // Ambil semua paket dari sesi yang sama (anak + tanggal yang sama)
        $semuaPaket = Rekomendasi::with([
            'menuSarapan.kandunganGizi',
            'menuSnackPagi.kandunganGizi',
            'menuMakanSiang.kandunganGizi',
            'menuSnackSore.kandunganGizi',
            'menuMakanMalam.kandunganGizi',
            'menuSnackMalam.kandunganGizi',
        ])
        ->where('anak_id', $rekTerpilih->anak_id)
        ->whereDate('tanggal_rekomendasi', $rekTerpilih->tanggal_rekomendasi)
        ->orderBy('ranking')
        ->get();

        // Distribusi kalori per slot — dihitung dari snapshot kebutuhan_kalori_harian
        // Menggunakan persentase resmi validasi ahli gizi (tidak memanggil AkgService ulang)
        $totalKalori = (float) $rekTerpilih->kebutuhan_kalori_harian;
        $distribusi  = [
            'Sarapan'     => ['persen' => 20, 'kkal' => round($totalKalori * 0.20, 2)],
            'Snack Pagi'  => ['persen' => 10, 'kkal' => round($totalKalori * 0.10, 2)],
            'Makan Siang' => ['persen' => 25, 'kkal' => round($totalKalori * 0.25, 2)],
            'Snack Sore'  => ['persen' => 10, 'kkal' => round($totalKalori * 0.10, 2)],
            'Makan Malam' => ['persen' => 25, 'kkal' => round($totalKalori * 0.25, 2)],
            'Snack Malam' => ['persen' => 10, 'kkal' => round($totalKalori * 0.10, 2)],
        ];

        // Bobot dan CR diambil dari snapshot bobot_kriteria (JSON) milik paket Peringkat 1
        // Ketiga paket dari satu sesi selalu punya bobot dan CR yang sama
        $paketP1 = $semuaPaket->firstWhere('ranking', 1);
        $bobot   = $paketP1?->bobot_kriteria ?? $rekTerpilih->bobot_kriteria;
        $cr      = (float) ($paketP1?->consistency_ratio ?? $rekTerpilih->consistency_ratio);

        // Label waktu makan (relasi antara key DB dan label tampilan)
        $labelWaktu = [
            'sarapan'     => 'Sarapan',
            'snack_pagi'  => 'Snack Pagi',
            'makan_siang' => 'Makan Siang',
            'snack_sore'  => 'Snack Sore',
            'makan_malam' => 'Makan Malam',
            'snack_malam' => 'Snack Malam',
        ];

        // Definisi kriteria untuk tampilan (nama, tipe, justifikasi)
        $kriteria = [
            ['kode' => 'K1', 'nama' => 'Kalori',      'tipe' => 'Target',  'kunci' => 'kalori'],
            ['kode' => 'K2', 'nama' => 'Karbohidrat', 'tipe' => 'Cost',    'kunci' => 'karbohidrat'],
            ['kode' => 'K3', 'nama' => 'Protein',     'tipe' => 'Benefit', 'kunci' => 'protein'],
            ['kode' => 'K4', 'nama' => 'Serat',       'tipe' => 'Benefit', 'kunci' => 'serat'],
        ];
        $calculationDetails = $this->fuzzyAhpService->getCalculationDetails();

        return view('admin.riwayat.show', compact(
            'pemilihan',
            'anak',
            'rekTerpilih',
            'semuaPaket',
            'totalKalori',
            'distribusi',
            'bobot',
            'cr',
            'labelWaktu',
            'kriteria',
            'calculationDetails',
        ));
    }
}
