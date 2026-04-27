<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\Menu;
use App\Models\Rekomendasi;
use App\Services\AkgService;
use App\Services\FuzzyAhpService;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function __construct(
        private AkgService      $akgService,
        private FuzzyAhpService $fuzzyAhp
    ) {}

    /**
     * GET /rekomendasi
     * Form input data anak + pilih waktu makan.
     */
    public function index()
    {
        return view('rekomendasi.index');
    }

    /**
     * POST /rekomendasi/proses
     *
     * Alur:
     * 1. Validasi + buat objek Anak sementara (tidak disimpan ke DB)
     * 2. Hitung kebutuhan kalori via AkgService
     * 3. Ambil menu + gizi dari DB
     * 4. Ranking via FuzzyAhpService
     * 5. Simpan hasil ranking ke tabel rekomendasi
     * 6. Tampilkan hasil
     */
    public function proses(Request $request)
    {
        $request->validate([
            'nama'              => ['required', 'string', 'max:100'],
            'jenis_kelamin'     => ['required', 'in:L,P'],
            'tanggal_lahir'     => ['required', 'date', 'before:today'],
            'berat_badan'       => ['required', 'numeric', 'min:5', 'max:150'],
            'tinggi_badan'      => ['required', 'numeric', 'min:50', 'max:250'],
            'tingkat_aktivitas' => ['required', 'in:ringan,sedang,berat'],
            'waktu_makan'       => ['required', 'in:sarapan,makan_siang,makan_malam'],
        ]);

        // STEP 1: Simpan data anak ke DB
        // Data anak tetap disimpan agar hasil rekomendasi punya referensi
        $anak = Anak::create([
            'nama'              => $request->nama,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'berat_badan'       => $request->berat_badan,
            'tinggi_badan'      => $request->tinggi_badan,
            'tingkat_aktivitas' => $request->tingkat_aktivitas,
        ]);

        $waktuMakan = $request->waktu_makan;

        // STEP 2: Hitung kebutuhan kalori (AkgService)
        $totalKalori = $this->akgService->hitungKebutuhanKalori($anak);

        $distribusi = [
            'sarapan'     => $totalKalori * 0.30,
            'makan_siang' => $totalKalori * 0.40,
            'makan_malam' => $totalKalori * 0.30,
        ];
        $kaloriTarget = $distribusi[$waktuMakan];

        // STEP 3: Ambil menu + gizi dari DB
        $menus = Menu::with('kandunganGizi')
                     ->where('jenis_menu', $waktuMakan)
                     ->where('is_active', true)
                     ->get();

        if ($menus->isEmpty()) {
            return back()->with('error', 'Tidak ada menu tersedia untuk waktu makan ini.');
        }

        // STEP 4: Ranking via Fuzzy AHP
        $ranked = $this->fuzzyAhp->rankMenus($menus, $kaloriTarget);
        $cr     = $this->fuzzyAhp->getConsistencyRatio();

        // STEP 5: Simpan hasil ranking ke DB
        foreach ($ranked as $item) {
            Rekomendasi::create([
                'anak_id'                 => $anak->id,
                'menu_id'                 => $item['menu_id'],
                'tanggal_rekomendasi'     => today(),
                'waktu_makan'             => $waktuMakan,
                'bobot_kriteria'          => $item['bobot_kriteria'],
                'nilai_preferensi'        => $item['skor'],
                'ranking'                 => $item['ranking'],
                'kebutuhan_kalori_harian' => $totalKalori,
                'consistency_ratio'       => $cr,
                'status'                  => 'aktif',
            ]);
        }

        // STEP 6: Tampilkan hasil
        return view('rekomendasi.hasil', [
            'anak'         => $anak,
            'ranked'       => $ranked,
            'menus'        => $menus,
            'waktuMakan'   => $waktuMakan,
            'kaloriTarget' => round($kaloriTarget, 2),
            'totalKalori'  => round($totalKalori, 2),
            'cr'           => $cr,
            'crValid'      => $cr < 0.1,
        ]);
    }
}
