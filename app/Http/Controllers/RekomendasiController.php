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
            'nama' => ['required', 'string', 'max:100'],

            'jenis_kelamin' => ['required', 'in:L,P'],

            'tanggal_lahir' => [
                'required',
                'date',
                'before:' . now()->subYears(4)->toDateString(),   // minimal usia 4 tahun
                'after:' . now()->subYears(16)->toDateString(),   // maksimal usia 16 tahun
            ],

        ], [
            'nama.required' => 'Nama anak wajib diisi.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',

            'tanggal_lahir.before' => 'Usia anak minimal 4 tahun.',

            'tanggal_lahir.after' => 'Usia anak maksimal 16 tahun.',
        ]);

        // ── STEP 1: Simpan data anak ──────────────────────────
        $anak = Anak::create([
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
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
                'fahp_trace'              => $item['fahp_trace'],
            ]);

            $rekomendasiIds[$item['ranking']] = $rekomendasi->id;
        }

        // ── STEP 7: Redirect ke halaman hasil (PRG pattern) ──
        return redirect()->route('rekomendasi.hasil', $anak->id);
    }

    /**
     * GET /rekomendasi/hasil/{anak}
     *
     * Rekonstruksi data tampilan dari DB (tanpa kalkulasi ulang Fuzzy AHP).
     * Data $ranked disusun ulang dari tabel rekomendasi yang sudah tersimpan.
     */
    public function hasil(Anak $anak)
    {
        // Ambil 3 paket rekomendasi terakhir untuk anak ini (sesi terbaru)
        $tanggal = Rekomendasi::where('anak_id', $anak->id)
            ->latest('created_at')
            ->value('tanggal_rekomendasi');

        $rekomendasis = Rekomendasi::withAllMenus()
            ->where('anak_id', $anak->id)
            ->whereDate('tanggal_rekomendasi', $tanggal)
            ->orderBy('ranking')
            ->get();

        if ($rekomendasis->isEmpty()) {
            return redirect()->route('rekomendasi.index')
                ->with('error', 'Data rekomendasi tidak ditemukan.');
        }

        // Susun $ranked agar kompatibel dengan hasil.blade.php yang sudah ada
        $ranked = $rekomendasis->map(function (Rekomendasi $rek) {
            $menus    = $rek->semuaMenu();
            $gizi     = $this->hitungTotalGizi($menus);

            return [
                'ranking'        => $rek->ranking,
                'skor'           => (float) $rek->nilai_preferensi,
                'bobot_kriteria' => $rek->bobot_kriteria,
                'fahp_trace'     => $rek->fahp_trace,
                'paket'          => [
                    'menus'      => $menus,
                    'total_gizi' => $gizi,
                ],
            ];
        })->values()->all();

        $totalKalori    = (float) $rekomendasis->first()->kebutuhan_kalori_harian;
        $rekomendasiIds = $rekomendasis->pluck('id', 'ranking')->all();

        // Cek apakah sesi ini sudah ada pemilihan
        $pemilihan = \App\Models\PemilihanMenu::whereIn('rekomendasi_id', $rekomendasis->pluck('id'))
            ->latest()
            ->first();

        return view('rekomendasi.hasil', [
            'anak'           => $anak,
            'ranked'         => $ranked,
            'totalKalori'    => round($totalKalori, 2),
            'rekomendasiIds' => $rekomendasiIds,
            'labelWaktu'     => $this->labelWaktu(),
            'paketDipilih'   => $pemilihan?->ranking_dipilih,  // null jika belum memilih
        ]);
    }

    /**
     * Hitung total gizi 6 menu dalam satu paket dari relasi kandunganGizi.
     * Dipakai oleh hasil() untuk rekonstruksi tanpa memanggil Fuzzy AHP ulang.
     */
    private function hitungTotalGizi(array $menus): array
    {
        $total = ['kalori' => 0, 'karbohidrat' => 0, 'protein' => 0, 'serat' => 0];

        foreach ($menus as $menu) {
            if ($menu?->kandunganGizi) {
                $g = $menu->kandunganGizi;
                $total['kalori']      += (float) $g->energi_kkal;
                $total['karbohidrat'] += (float) $g->karbohidrat_gram;
                $total['protein']     += (float) $g->protein_gram;
                $total['serat']       += (float) $g->serat_gram;
            }
        }

        return $total;
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
