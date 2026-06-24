<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\Rekomendasi;
use App\Services\AkgService;
use App\Services\FuzzyAhpService;
use App\Services\PaketHarianService;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function __construct(
        private AkgService         $akgService,
        private FuzzyAhpService    $fuzzyAhp,
        private PaketHarianService $paketService,
    ) {}

    /**
     * GET /rekomendasi
     * Form input data anak.
     */
    public function index()
    {
        return view('rekomendasi.index');
    }

    /**
     * POST /rekomendasi/proses
     *
     * Alur sistem final:
     * 1. Validasi & simpan data anak
     * 2. Hitung kebutuhan kalori harian (AKG 2019, tanpa PAL)
     * 3. Distribusi energi ke 6 waktu makan (20/10/25/10/25/10)
     * 4. Bentuk 3 paket kandidat (berbasis kesesuaian kalori AKG per slot)
     * 5. Ranking 3 paket dengan Fuzzy AHP (4 kriteria: kalori, karbo, protein, serat)
     * 6. Simpan 3 paket berperingkat ke tabel rekomendasi
     * 7. Tampilkan Peringkat 1, 2, 3
     */
    public function proses(Request $request)
    {
        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(18)->toDateString(),
            ],
            'berat_badan' => 'required|numeric|min:1',
            'tinggi_badan' => 'required|numeric|min:1',
            'tingkat_aktivitas' => 'required',
        ], [
            'tanggal_lahir.after' => 'Sistem ini ditujukan untuk anak usia 1–18 tahun.',
        ]);

        // ── STEP 1: Simpan data anak ──────────────────────────
        $anak = Anak::create([
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'tingkat_aktivitas' => $request->tingkat_aktivitas,
        ]);

        // ── STEP 2: Hitung kebutuhan kalori (AKG 2019) ───────
        // AkgService menggunakan $anak->usia (computed dari tanggal_lahir)
        $totalKalori = $this->akgService->hitungKebutuhanKalori($anak);

        // ── STEP 3: Distribusi ke 6 waktu makan ──────────────
        $distribusi = $this->akgService->distribusiKalori($totalKalori);

        // ── STEP 4: Bentuk 3 paket kandidat ──────────────────
        $paketKandidat = $this->paketService->generatePaketKandidat($distribusi);

        // ── STEP 5: Ranking paket dengan Fuzzy AHP ───────────
        $ranked = $this->fuzzyAhp->rankPaket($paketKandidat, $totalKalori);
        $cr     = $this->fuzzyAhp->getConsistencyRatio();

        // ── STEP 6: Simpan 3 paket ke database ───────────────
        $rekomendasiIds = [];

        foreach ($ranked as $item) {
            $paket = $item['paket'];
            $menus = $paket['menus'];

            $rekomendasi = Rekomendasi::create([
                'anak_id'                 => $anak->id,
                'tanggal_rekomendasi'     => today(),
                'ranking'                 => $item['ranking'],
                'menu_sarapan_id'         => $menus['sarapan']?->id,
                'menu_snack_pagi_id'      => $menus['snack_pagi']?->id,
                'menu_makan_siang_id'     => $menus['makan_siang']?->id,
                'menu_snack_sore_id'      => $menus['snack_sore']?->id,
                'menu_makan_malam_id'     => $menus['makan_malam']?->id,
                'menu_snack_malam_id'     => $menus['snack_malam']?->id,
                'kebutuhan_kalori_harian' => $totalKalori,
                'total_kalori_paket'      => $paket['total_gizi']['kalori'],
                'nilai_preferensi'        => $item['skor'],
                'bobot_kriteria'          => $item['bobot_kriteria'],
                'consistency_ratio'       => $cr,
            ]);

            $rekomendasiIds[$item['ranking']] = $rekomendasi->id;
        }

        // ── STEP 7: Tampilkan hasil ───────────────────────────
        return view('rekomendasi.hasil', [
            'anak'           => $anak,
            'ranked'         => $ranked,
            'distribusi'     => $distribusi,
            'totalKalori'    => round($totalKalori, 2),
            'rekomendasiIds' => $rekomendasiIds,
            'labelWaktu'     => $this->labelWaktu(),
        ]);
    }

    /**
     * Label tampilan untuk 6 waktu makan.
     */
    private function labelWaktu(): array
    {
        return [
            'sarapan'     => 'Sarapan',
            'snack_pagi'  => 'Snack Pagi',
            'makan_siang' => 'Makan Siang',
            'snack_sore'  => 'Snack Sore',
            'makan_malam' => 'Makan Malam',
            'snack_malam' => 'Snack Malam',
        ];
    }
}
