<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\KategoriSkillModel;
use App\Models\KotaModel;
use App\Models\MahasiswaModel;
use App\Models\ProvinsiModel;
use App\Models\TipeKerjaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LowonganController extends Controller
{
    public function index()
    {
        $listKota     = KotaModel::all();
        $listKategori = KategoriSkillModel::all();
        $activeMenu   = 'lowongan';

        return view('mahasiswa_page.lowongan.index', compact(
            'activeMenu',
            'listKota',
            'listKategori'
        ));
    }

    public function list(Request $request)
    {

        $today = Carbon::today();

        $query = DetailLowonganModel::with(['industri.kota', 'kategoriSkill'])
            ->select('m_detail_lowongan.*')
            ->whereDate('pendaftaran_tanggal_mulai', '<=', $today)
            ->whereDate('pendaftaran_tanggal_selesai', '>=', $today); // Perhatikan perubahan di sini

        // Filter berdasarkan lokasi (kota)
        if ($request->has('lokasi') && $request->lokasi != '') {
            $query->whereHas('industri.kota', function ($q) use ($request) {
                $q->where('kota_id', $request->lokasi);
            });
        }

        // Filter berdasarkan jenis (kategori skill)
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('kategori_skill_id', $request->jenis);
        }

        // Pencarian global
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('judul_lowongan', 'like', "%{$search}%")
                    ->orWhereHas('industri', function ($q) use ($search) {
                        $q->where('industri_nama', 'like', "%{$search}%");
                    });
            });
        }

        return DataTables::of($query)
            ->addColumn('industri', function ($row) {
                $logo = $row->industri->logo
                ? asset('storage/logo_industri/' . $row->industri->logo)
                : asset('assets/default-industri.png');

                $nama     = $row->industri->industri_nama ?? '-';
                $kota     = $row->industri->kota->kota_nama ?? '-';
                $provinsi = $row->industri->kota->provinsi->provinsi_nama ?? '-';

                return '
            <div class="d-flex px-2 py-1">
                <div>
                    <img src="' . $logo . '" class="avatar avatar-sm me-3" alt="logo industri">
                </div>
                <div class="d-flex flex-column justify-content-center text-start">
                    <h6 class="mb-0 text-sm">' . $nama . '</h6>
                    <p class="text-xs text-secondary mb-0">' . $kota . ', ' . $provinsi . '</p>
                </div>
            </div>';
            })
            ->addColumn('jenis', fn($row) => $row->kategoriSkill->kategori_nama ?? '-')
            ->addColumn('judul', fn($row) => $row->judul_lowongan ?? '-')
            ->addColumn('slot', fn($row) => $row->slotTersedia())
            ->addColumn('periode', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') . ' - ' .
                \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y');
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('/mahasiswa/lowongan/' . $row->lowongan_id . '/show') . '\')" class="fw-bold text-success bg-transparent border-0 p-0">Detail</button>';
            })
            ->rawColumns(['industri', 'aksi'])
            ->make(true);
    }
    public function show($id)
    {
        $lowongan = DetailLowonganModel::with([
            'industri.kota.provinsi',
            'kategoriSkill',
            'lowonganSkill.skill',
            'fasilitas', // <-- Tambahkan ini
            'tipeKerja', // <-- Tambahkan ini
        ])->findOrFail($id);

        return view('mahasiswa_page.lowongan.show', compact('lowongan'));
    }

    public function getLowonganDetailJson(DetailLowonganModel $lowongan)
    {
        try {
            // Eager load relasi yang dibutuhkan untuk menghindari N+1 query
            $lowongan->load([
                'industri.kota.provinsi', // industri, lalu kota dari industri, lalu provinsi dari kota
                'kategoriSkill',
                'lowonganSkill.skill', // lowonganSkill, dan skill dari setiap lowonganSkill
            ]);

            // Memproses skill yang dibutuhkan
            $required_skills = $lowongan->lowonganSkill->map(function ($lowonganSkill) {
                return [
                    'nama_skill'       => optional($lowonganSkill->skill)->skill_nama ?? 'N/A', // Menggunakan optional() untuk keamanan
                    'level_kompetensi' => $lowonganSkill->level_kompetensi ?? 'N/A',
                    // 'bobot'           => $lowonganSkill->bobot ?? 'N/A', // Uncomment jika Anda ingin mengirim bobot skill lowongan
                ];
            });

            // Mengambil data industri dan lokasi dengan aman
            $industri      = $lowongan->industri;
            $kota          = optional($industri)->kota;
            $provinsi      = optional($kota)->provinsi;
            $lokasi_string = trim((optional($kota)->kota_nama ?? '') . ($kota && $provinsi ? ', ' . optional($provinsi)->provinsi_nama : ''), ', ');

            // Menyusun data untuk respons JSON
            $data = [
                'lowongan_id'                    => $lowongan->lowongan_id,
                'judul_lowongan'                 => $lowongan->judul_lowongan ?? 'Judul Tidak Tersedia',
                'industri_nama'                  => optional($industri)->industri_nama ?? '-',
                'logo_industri_url'              => (optional($industri)->logo) ? asset('storage/logo_industri/' . $industri->logo) : asset('assets/default-industri.png'), // Pastikan path default benar
                'deskripsi_lengkap'              => nl2br(htmlspecialchars($lowongan->deskripsi ?? '')),
                'kategori_nama'                  => optional($lowongan->kategoriSkill)->kategori_nama ?? 'Umum',

                'periode_magang'                 => ($lowongan->tanggal_mulai && $lowongan->tanggal_selesai)
                ? (Carbon::parse($lowongan->tanggal_mulai)->isoFormat('D MMMM YYYY') . ' - ' . Carbon::parse($lowongan->tanggal_selesai)->isoFormat('D MMMM YYYY'))
                : 'Tidak ditentukan',
                // Menyediakan format tanggal Y-m-d untuk JavaScript (misal untuk date picker min/max)
                'periode_magang_raw'             => [
                    'start' => optional($lowongan->tanggal_mulai)->format('Y-m-d'),
                    'end'   => optional($lowongan->tanggal_selesai)->format('Y-m-d'),
                ],

                'periode_pendaftaran'            => ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                ? (Carbon::parse($lowongan->pendaftaran_tanggal_mulai)->isoFormat('D MMMM YYYY') . ' s/d ' . Carbon::parse($lowongan->pendaftaran_tanggal_selesai)->isoFormat('D MMMM YYYY'))
                : 'Tidak ditentukan',

                // Pastikan accessor ini ada di DetailLowonganModel dan aman terhadap null
                'status_pendaftaran_text'        => $lowongan->status_pendaftaran_text ?? 'Status Tidak Diketahui',
                'status_pendaftaran_badge_class' => $lowongan->status_pendaftaran_badge_class ?? 'bg-secondary',

                'lokasi'                         => $lokasi_string ?: 'Lokasi Tidak Dicantumkan', // Tampilkan pesan jika lokasi kosong

                // Pastikan method slotTersedia() ada di DetailLowonganModel dan aman
                'slot_tersedia'                  => method_exists($lowongan, 'slotTersedia') ? $lowongan->slotTersedia() : ($lowongan->slot ?? 0),
                'total_slot'                     => $lowongan->slot ?? 0,
                'required_skills'                => $required_skills,
            ];

            return response()->json(['status' => true, 'data' => $data]);

        } catch (\Exception $e) {
            // Log error dengan lebih detail, termasuk stack trace
            Log::error("Error fetching lowongan detail JSON for ID {$lowongan->lowongan_id}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['status' => false, 'message' => 'Gagal memuat detail lowongan. Silakan coba lagi nanti.'], 500);
        }
    }

    public function showRecommendationModal()
    {
        // Daftar kriteria yang akan dinilai oleh mahasiswa
        $kriteria = [
            'skill'      => 'Seberapa penting kesesuaian skill Anda dengan lowongan?',
            'lokasi'     => 'Seberapa penting kedekatan lokasi magang?',
            'upah'       => 'Seberapa penting uang saku/upah yang diberikan?',
            'fasilitas'  => 'Seberapa penting fasilitas yang disediakan?',
            'alumni'     => 'Seberapa penting adanya alumni dari prodi Anda di perusahaan?',
            'tipe_kerja' => 'Seberapa penting tipe kerja (On-Site, Hybrid, WFH)?', // <-- Kriteria Baru
        ];

        // Ambil data untuk form preferensi
        $provinsiList  = ProvinsiModel::orderBy('provinsi_nama')->get();
        $tipeKerjaList = TipeKerjaModel::all();

        return view('mahasiswa_page.lowongan.rekomendasi.modal', compact('kriteria', 'provinsiList', 'tipeKerjaList'));
    }

    /**
     * Menghitung dan menampilkan hasil rekomendasi menggunakan COPRAS.
     */
    public function calculateRecommendation(Request $request)
    {
        // Log saat request pertama kali masuk
        Log::info('--- MEMULAI PERHITUNGAN REKOMENDASI ---');
        Log::info('Input Diterima:', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'pref_provinsi_id'  => 'required|exists:m_provinsi,provinsi_id',
            'pref_kota_id'      => 'required|exists:m_kota,kota_id',
            'pref_tipe_kerja'   => 'nullable|array',
            'pref_tipe_kerja.*' => 'exists:m_tipe_kerja,tipe_kerja_id',
            'bobot'             => 'required|array',
            'bobot.*'           => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi Gagal:', $validator->errors()->toArray());
            // Jika validasi gagal, kembalikan response JSON dengan error
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ambil data mahasiswa
        $mahasiswa = MahasiswaModel::with('skills.detailSkill')->find(Auth::id());
        if (! $mahasiswa) {
            Log::error('Autentikasi Mahasiswa Gagal: User tidak ditemukan.');
            return response('Data mahasiswa tidak ditemukan.', 500);
        }
        Log::info('Mahasiswa ditemukan: ' . $mahasiswa->nama_lengkap);

        // ==========================================================
        // BAGIAN PENTING YANG HILANG: MENGAMBIL DATA ALTERNATIF
        // ==========================================================
        $alternatives = DetailLowonganModel::with(['industri.kota.provinsi', 'fasilitas', 'lowonganSkill.skill', 'tipeKerja'])
            ->where('pendaftaran_tanggal_selesai', '>=', now()) // Hanya ambil lowongan yang masih buka
            ->get();

        if ($alternatives->isEmpty()) {
            Log::warning('Tidak ada lowongan aktif yang ditemukan untuk dijadikan alternatif.');
            return '<div class="alert alert-info">Saat ini tidak ada lowongan aktif untuk direkomendasikan.</div>';
        }
        Log::info('Total alternatif lowongan yang ditemukan: ' . $alternatives->count());

        $bobotInput   = $request->bobot;
        $kriteriaList = array_keys($bobotInput);

        // Langkah 1: Membuat Matriks Keputusan (X)
        Log::info('Memulai pembuatan Matriks Keputusan (X)');
        $matriks_X       = [];
        $mahasiswaSkills = $mahasiswa->skills->pluck('skill_id')->toArray();
        $prefKotaId      = $request->pref_kota_id;
        $prefProvinsiId  = $request->pref_provinsi_id;
        $prefTipeKerja   = $request->pref_tipe_kerja ?? [];

        foreach ($alternatives as $alt) {
            // ... (seluruh logika perhitungan nilai C1 s/d C6 Anda tetap sama)
            $nilai          = [];
            $lowonganSkills = $alt->lowonganSkill->pluck('skill_id')->toArray();
            $skillCocok     = count(array_intersect($mahasiswaSkills, $lowonganSkills));
            $nilai['skill'] = count($lowonganSkills) > 0 ? ($skillCocok / count($lowonganSkills)) * 100 : 0;

            $lokasiLowonganKotaId     = $alt->use_specific_location ? $alt->lokasi_kota_id : $alt->industri->kota_id;
            $lokasiLowonganProvinsiId = $alt->use_specific_location ? $alt->lokasi_provinsi_id : (optional($alt->industri->kota)->provinsi_id ?? null);
            if ($lokasiLowonganKotaId == $prefKotaId) {$nilai['lokasi'] = 5;} elseif ($lokasiLowonganProvinsiId == $prefProvinsiId) {$nilai['lokasi'] = 3;} else { $nilai['lokasi'] = 1;}

            $nilai['upah']      = $alt->upah > 0 ? $alt->upah : 1;
            $nilai['fasilitas'] = $alt->fasilitas->count();
            $nilai['alumni']    = $alt->industri->alumni_count > 0 ? $alt->industri->alumni_count : 1;

            $lowonganTipeKerja   = $alt->tipeKerja->pluck('tipe_kerja_id')->toArray();
            $tipeKerjaCocok      = count(array_intersect($prefTipeKerja, $lowonganTipeKerja));
            $nilai['tipe_kerja'] = count($prefTipeKerja) > 0 ? ($tipeKerjaCocok / count($prefTipeKerja)) * 100 : 100;

            $matriks_X[$alt->lowongan_id] = $nilai;
        }
        Log::info('Matriks Keputusan (X) berhasil dibuat.');

        // Langkah 2: Normalisasi Bobot (W)
        $totalBobot = array_sum($bobotInput);
        $bobot_W    = [];
        foreach ($bobotInput as $key => $val) {
            $bobot_W[$key] = $totalBobot > 0 ? $val / $totalBobot : 0;
        }
        Log::info('Normalisasi Bobot (W) berhasil.');

        // Langkah 3: Normalisasi Matriks Keputusan (R)
        $matriks_R        = [];
        $totalPerKriteria = [];
        foreach ($kriteriaList as $k) {
            $totalPerKriteria[$k] = array_sum(array_column($matriks_X, $k));
        }
        foreach ($matriks_X as $id => $nilai) {
            foreach ($kriteriaList as $k) {
                $matriks_R[$id][$k] = $totalPerKriteria[$k] > 0 ? $nilai[$k] / $totalPerKriteria[$k] : 0;
            }
        }
        Log::info('Normalisasi Matriks (R) berhasil.');

        // ==========================================================
        // LANGKAH 4: MATRIKS NORMALISASI TERBOBOT (V)
        // ==========================================================
        $matriks_V = [];
        foreach ($matriks_R as $id => $nilai) {
            foreach ($kriteriaList as $k) {
                $matriks_V[$id][$k] = $nilai[$k] * $bobot_W[$k];
            }
        }

        // ==========================================================
        // LANGKAH 5: HITUNG NILAI S+i DAN S-i
        // ==========================================================
        $nilai_S_plus  = [];
        $nilai_S_minus = []; // Dalam kasus ini, semua kriteria adalah benefit, jadi S- akan 0
        foreach ($matriks_V as $id => $nilai) {
            $nilai_S_plus[$id]  = array_sum($nilai);
            $nilai_S_minus[$id] = 0; // Karena tidak ada kriteria cost
        }

        // ==========================================================
        // LANGKAH 6: HITUNG NILAI RELATIVE SIGNIFICANCE (Q)
        // ==========================================================
        $nilai_Q = [];
        // Karena S-i semua 0, rumus COPRAS disederhanakan menjadi Q_i = S_+i
        foreach ($nilai_S_plus as $id => $val) {
            $nilai_Q[$id] = $val;
        }

        // ==========================================================
        // LANGKAH 7: HITUNG NILAI UTILITAS (N) DAN RANKING
        // ==========================================================
        $nilai_N = [];
        $Q_max   = ! empty($nilai_Q) ? max($nilai_Q) : 0;
        foreach ($nilai_Q as $id => $val) {
            $nilai_N[$id] = $Q_max > 0 ? ($val / $Q_max) * 100 : 0;
        }
        arsort($nilai_N); // Urutkan dari tertinggi ke terendah

        // Kirim semua data perhitungan ke view
        return view('mahasiswa_page.lowongan.rekomendasi.hasil', compact(
            'alternatives', 'kriteriaList', 'bobotInput',
            'matriks_X', 'bobot_W', 'totalPerKriteria', 'matriks_R', 'matriks_V',
            'nilai_S_plus', 'nilai_S_minus', 'nilai_Q', 'nilai_N'
        ));
    }
}
