<?php
namespace App\Http\Controllers\industri;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\DetailSkillModel;
use App\Models\IndustriModel;
use App\Models\KategoriSkillModel;
use App\Models\KotaModel;
use App\Models\LowonganSkillModel;
use App\Models\MagangModel;
use App\Models\PengajuanModel;
use App\Models\ProvinsiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// Ditambahkan untuk Request jika diperlukan
// Untuk transaction jika diperlukan
// Untuk validasi

class LowonganController extends Controller
{
    public function index()
    {
        // Ambil industri yang sedang login beserta lowongannya
        $industri = IndustriModel::where('industri_id', Auth::id())
            ->with([
                'detail_lowongan' => function ($query) {
                },
            ])
            ->firstOrFail();

        return view('industri_page.lowongan.index', [
            'activeMenu'        => 'lowongan',
            'industri'          => $industri,
            'lowongan_industri' => $industri->detail_lowongan,
        ]);
    }

    public function show($id) // Hapus Request $request jika tidak digunakan
    {
        $lowongan = DetailLowonganModel::with([
            'industri',
            'kategoriSkill',
            'lowonganSkill.skill',
            'pendaftar.mahasiswa', // Eager load pendaftar dan data mahasiswa mereka
        ])->findOrFail($id);

        $activeMenu = 'lowongan';

        // Pastikan view 'industri_page.lowongan.show' ada di resources/views/industri_page/lowongan/show.blade.php
        return view('industri_page.lowongan.show', compact('lowongan', 'activeMenu'));
    }
    public function create()
    {
        $kategoriSkills = KategoriSkillModel::orderBy('kategori_nama')->get();
        $detailSkills   = DetailSkillModel::orderBy('skill_nama')->get();
        $industri       = Auth::user(); // Pastikan Auth::user() adalah instance IndustriModel yang memiliki relasi/properti yang dibutuhkan
        $activeMenu     = 'lowongan';

        // Ambil daftar semua provinsi
        $provinsiList = ProvinsiModel::orderBy('provinsi_nama')->get();
        // Ambil daftar semua kota beserta provinsi_id nya
        $kotaList = KotaModel::orderBy('kota_nama')->select('kota_id', 'kota_nama', 'provinsi_id')->get();

        // Pastikan $industri valid dan memiliki industri_id
        // Logika ini perlu disesuaikan dengan struktur User dan IndustriModel Anda
        $industriId = null;
        if ($industri) {
            if (isset($industri->industri_id)) { // Jika Auth::user() langsung instance IndustriModel dengan properti industri_id
                $industriId = $industri->industri_id;
            } elseif (method_exists($industri, 'getKey') && $industri->getTable() === 'm_industri') { // Jika Auth::user() adalah IndustriModel
                $industriId = $industri->getKey();
            }
            // Anda mungkin perlu logika tambahan jika Auth::user() adalah User umum yang berelasi ke IndustriModel
        }

        if (! $industriId) {
            return redirect()->route('industri.lowongan.index')->with('error', 'Akses tidak sah atau data industri tidak ditemukan.');
        }
        // Jika Anda perlu mengambil ulang model Industri berdasarkan ID yang didapat:
        // $industri = IndustriModel::with('kota.provinsi')->find($industriId);
        // if(!$industri) {
        //      return redirect()->route('industri.lowongan.index')->with('error', 'Data industri tidak ditemukan.');
        // }

        return view('industri_page.lowongan.create', compact(
            'kategoriSkills',
            'detailSkills',
            'industri', // Kirim instance IndustriModel yang sudah di-load dengan relasi jika perlu di view
            'activeMenu',
            'provinsiList',
            'kotaList' // <-- KIRIM DAFTAR KOTA KE VIEW
        ));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_lowongan'              => 'required|string|max:255',
            'kategori_skill_id'           => 'required|exists:m_kategori_skill,kategori_skill_id',
            'slot'                        => 'required|integer|min:1',
            'deskripsi'                   => 'required|string',
            'tanggal_mulai'               => 'required|date',
            'tanggal_selesai'             => 'required|date|after_or_equal:tanggal_mulai',
            'pendaftaran_tanggal_mulai'   => 'required|date',
            'pendaftaran_tanggal_selesai' => 'required|date|after_or_equal:pendaftaran_tanggal_mulai',

            'skills'                      => 'sometimes|array',
            'skills.*'                    => 'required_with:skills|exists:m_detail_skill,skill_id',
            'levels'                      => 'sometimes|array',
            'levels.*'                    => 'required_with:skills|string|in:Beginner,Intermediate,Expert', // Validasi untuk level

            // Validasi untuk bobot kriteria lainnya (IPK & Lokasi)
            'bobot_akademik'              => 'required|numeric|min:1|max:100',
            'bobot_lokasi'                => 'required|numeric|min:1|max:100',

                                                                 // Validasi untuk alamat spesifik lowongan
            'use_specific_location'       => 'nullable|boolean', // checkbox bisa tidak dikirim jika tidak dicentang
            'lokasi_provinsi_id'          => 'required_if:use_specific_location,1|nullable|exists:m_provinsi,provinsi_id',
            'lokasi_kota_id'              => 'required_if:use_specific_location,1|nullable|exists:m_kota,kota_id',
            'lokasi_alamat_lengkap'       => 'required_if:use_specific_location,1|nullable|string|max:1000',

        ], [
            'kategori_skill_id.required'        => 'Kategori lowongan wajib dipilih.',
            'kategori_skill_id.exists'          => 'Kategori lowongan tidak valid.',
            'skills.*.exists'                   => 'Salah satu skill yang dipilih tidak valid.',
            'levels.*.required_with'            => 'Level kompetensi untuk setiap skill wajib dipilih.',
            'levels.*.in'                       => 'Level kompetensi tidak valid.',
            'bobot_akademik.required'           => 'Bobot nilai akademik wajib diisi.',
            'bobot_lokasi.required'             => 'Bobot lokasi wajib diisi.',
            'lokasi_provinsi_id.required_if'    => 'Provinsi spesifik wajib dipilih jika menggunakan alamat berbeda.',
            'lokasi_kota_id.required_if'        => 'Kota spesifik wajib dipilih jika menggunakan alamat berbeda.',
            'lokasi_alamat_lengkap.required_if' => 'Alamat lengkap spesifik wajib diisi jika menggunakan alamat berbeda.',

            // Tambahkan pesan validasi untuk alamat jika perlu
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Pastikan jumlah skills dan levels cocok jika ada
        if ($request->has('skills') && $request->has('levels')) {
            if (count($request->input('skills')) !== count($request->input('levels'))) {
                return redirect()->back()
                    ->withErrors(['skills' => 'Jumlah skill dan level kompetensi tidak cocok.'])
                    ->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Dapatkan industri_id dari user yang terautentikasi
            // Penting: Sesuaikan logika ini dengan bagaimana Anda mengelola autentikasi industri
            $loggedInUser = Auth::user();
            $industriId   = null;

            if ($loggedInUser instanceof IndustriModel) {
                $industriId = $loggedInUser->industri_id;             // Atau $loggedInUser->getKey() jika PKnya adalah industri_id
            } elseif (method_exists($loggedInUser, 'industri')) { // Jika ada relasi 'industri' di model User standar
                $industriRelasi = $loggedInUser->industri;            // Misal relasi HasOne atau BelongsTo ke IndustriModel
                if ($industriRelasi) {
                    $industriId = $industriRelasi->industri_id; // Atau $industriRelasi->getKey()
                }
            } else if (isset($loggedInUser->industri_id)) { // Jika ada properti industri_id langsung
                $industriId = $loggedInUser->industri_id;
            }

            if (! $industriId) {
                throw new \Exception("Tidak dapat menemukan ID Industri yang terautentikasi atau user bukan merupakan industri.");
            }

            $useSpecificLocation = $request->boolean('use_specific_location');

            $lowonganData = [
                'judul_lowongan'              => $request->judul_lowongan,
                'kategori_skill_id'           => $request->kategori_skill_id,
                'slot'                        => $request->slot,
                'deskripsi'                   => $request->deskripsi,
                'tanggal_mulai'               => $request->tanggal_mulai,
                'tanggal_selesai'             => $request->tanggal_selesai,
                'pendaftaran_tanggal_mulai'   => $request->pendaftaran_tanggal_mulai,
                'pendaftaran_tanggal_selesai' => $request->pendaftaran_tanggal_selesai,
                'industri_id'                 => $industriId,
                'use_specific_location'       => $useSpecificLocation,
            ];

            if ($useSpecificLocation) {
                $lowonganData['lokasi_provinsi_id']    = $request->lokasi_provinsi_id;
                $lowonganData['lokasi_kota_id']        = $request->lokasi_kota_id;
                $lowonganData['lokasi_alamat_lengkap'] = $request->lokasi_alamat_lengkap;
            } else {
                // Set ke null jika tidak menggunakan alamat spesifik
                $lowonganData['lokasi_provinsi_id']    = null;
                $lowonganData['lokasi_kota_id']        = null;
                $lowonganData['lokasi_alamat_lengkap'] = null;
            }

            // Cek apakah model DetailLowonganModel memiliki fillable untuk bobot_akademik dan bobot_lokasi
            // Jika tidak, Anda perlu menyimpannya ke tabel KriteriaMagangModel
            // Untuk saat ini, kita asumsikan belum disimpan langsung di DetailLowonganModel

            $lowongan = DetailLowonganModel::create($lowonganData);

            // Simpan bobot ke tabel kriteria_magang jika KriteriaMagangModel ada
            // Asumsi KriteriaMagangModel memiliki lowongan_id, nama_kriteria, bobot
            // if (class_exists(\App\Models\KriteriaMagangModel::class)) {
            //     \App\Models\KriteriaMagangModel::updateOrCreate(
            //         ['lowongan_id' => $lowongan->lowongan_id, 'nama_kriteria' => 'Akademik (IPK)'],
            //         ['bobot' => $request->bobot_akademik]
            //     );
            //     \App\Models\KriteriaMagangModel::updateOrCreate(
            //         ['lowongan_id' => $lowongan->lowongan_id, 'nama_kriteria' => 'Lokasi'],
            //         ['bobot' => $request->bobot_lokasi]
            //     );
            // }

            if ($request->has('skills')) {
                foreach ($request->input('skills') as $index => $skillId) {
                    $levelKompetensi = $request->input('levels')[$index] ?? 'Beginner'; // default jika tidak ada
                    $bobotNumerik    = 0;

                    // Konversi level ke bobot numerik (sesuaikan nilai bobot ini)
                    switch ($levelKompetensi) {
                        case 'Beginner':
                            $bobotNumerik = 30; // Contoh bobot
                            break;
                        case 'Intermediate':
                            $bobotNumerik = 60; // Contoh bobot
                            break;
                        case 'Expert':
                            $bobotNumerik = 90; // Contoh bobot
                            break;
                    }

                    if (! empty($skillId)) {
                        LowonganSkillModel::create([
                            'lowongan_id'      => $lowongan->lowongan_id,
                            'skill_id'         => $skillId,
                            'level_kompetensi' => $levelKompetensi,
                            'bobot'            => $bobotNumerik,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('industri.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error: Log::error('Error saat simpan lowongan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan lowongan: ' . $e->getMessage())->withInput();
        }
    }
    public function showPendaftarProfil(PengajuanModel $pengajuan)
    {
        $activeMenu = 'lowongan'; // Atau menu spesifik jika ada

        // Autorisasi: Pastikan industri yang login berhak melihat pengajuan ini
        // (yaitu pengajuan ini untuk salah satu lowongan milik industri tersebut)
        $lowonganIndustriId      = $pengajuan->lowongan->industri_id;
        $authenticatedIndustriId = Auth::id(); // Asumsi Auth::id() mengembalikan industri_id untuk guard industri

        // Jika Auth::user() adalah instance IndustriModel, maka:
        // $authenticatedIndustriId = Auth::user()->industri_id; atau Auth::user()->getKey();

        if ($lowonganIndustriId !== $authenticatedIndustriId) {
            // Atau jika Anda menggunakan policy/gate: if (Gate::denies('view-pendaftar', $pengajuan)) { ... }
            return redirect()->route('industri.lowongan.index')->with('error', 'Anda tidak berhak mengakses data pendaftar ini.');
        }

        // Eager load data yang dibutuhkan untuk mahasiswa dan lowongan terkait
        $pengajuan->load([
            'mahasiswa' => function ($query) {
                $query->with([
                    'prodi', // Untuk menampilkan prodi mahasiswa
                    'skills' => function ($skillQuery) {
                        $skillQuery->with(['detailSkill.kategori', 'linkedPortofolios.portofolio']) // Ambil detail skill, kategori, dan portofolio yang terhubung ke skill
                            ->where('status_verifikasi', 'Valid');                                      // Opsional: Hanya tampilkan skill yang sudah divalidasi DPA
                    },
                    // 'portofolios' // Jika ingin menampilkan semua portofolio mahasiswa secara umum, bukan hanya yang terlink ke skill
                ]);
            },
            'lowongan'  => function ($query) {
                $query->with(['lowonganSkill.skill', 'kategoriSkill']); // Skill yang dibutuhkan lowongan & kategori lowongan
            },
        ]);

        // Ambil semua portofolio mahasiswa secara terpisah jika ingin ditampilkan semua
        $allPortfolioItems = $pengajuan->mahasiswa->portofolios()->orderBy('created_at', 'desc')->get();

        return view('industri_page.lowongan.pendaftar', compact(
            'pengajuan',
            'allPortfolioItems',
            'activeMenu'
        ));
    }
    public function terimaPengajuan(Request $request, PengajuanModel $pengajuan)
    {
                                                                                            // Autorisasi: Pastikan industri yang login berhak melakukan aksi ini
        $loggedInIndustri        = Auth::user();                                            // Asumsi Auth::user() adalah IndustriModel atau memiliki industri_id
        $authenticatedIndustriId = $loggedInIndustri->industri_id ?? $loggedInIndustri->id; // Sesuaikan

        if ($pengajuan->lowongan->industri_id !== $authenticatedIndustriId) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan untuk pengajuan ini.');
        }

        // Pastikan pengajuan masih dalam status 'belum' (bisa diterima)
        if (strtolower($pengajuan->status) !== 'belum') {
            return redirect()->back()->with('warning', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Cek slot tersedia (pastikan method slotTersedia() di DetailLowonganModel sudah benar
        // dan menghitung MagangModel dengan status 'belum' atau 'sedang')
        if ($pengajuan->lowongan->slotTersedia() <= 0) {
            return redirect()->back()->with('error', 'Slot untuk lowongan ini sudah penuh. Tidak dapat menerima pendaftar lagi.');
        }

        DB::beginTransaction();
        try {
                                             // 1. Update status pengajuan menjadi 'diterima'
            $pengajuan->status = 'diterima'; // Sesuai ENUM di t_pengajuan
            $pengajuan->save();

            // 2. Tambahkan mahasiswa ke tabel mahasiswa_magang (MagangModel)
            // Status awal di MagangModel adalah 'belum' (untuk "diterima, belum mulai")
            MagangModel::create([
                'mahasiswa_id' => $pengajuan->mahasiswa_id,
                'lowongan_id'  => $pengajuan->lowongan_id,
                'status'       => 'belum', // Sesuai ENUM ['belum', 'sedang', 'selesai'] di MagangModel
                                           // 'evaluasi'    => null, // Defaultnya akan null
            ]);

            DB::commit();
            // Redirect kembali ke halaman profil pendaftar dengan pesan sukses
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                ->with('success', 'Pengajuan mahasiswa ' . $pengajuan->mahasiswa->nama_lengkap . ' berhasil DITERIMA.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menerima pengajuan (ID: ' . $pengajuan->pengajuan_id . '): ' . $e->getMessage() . "\nStack: " . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat mencoba menerima pengajuan.');
        }
    }

    /**
     * Menolak pengajuan magang dari mahasiswa.
     */
    public function tolakPengajuan(Request $request, PengajuanModel $pengajuan)
    {
        $loggedInIndustri        = Auth::user();
        $authenticatedIndustriId = $loggedInIndustri->industri_id ?? $loggedInIndustri->id;

        if ($pengajuan->lowongan->industri_id !== $authenticatedIndustriId) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan untuk pengajuan ini.');
        }

        if (strtolower($pengajuan->status) !== 'belum') {
            return redirect()->back()->with('warning', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // TAMBAHKAN VALIDASI UNTUK ALASAN PENOLAKAN
        $validator = Validator::make($request->all(), [
            'alasan_penolakan' => 'nullable|string|max:1000', // Opsional, maksimal 1000 karakter
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Agar alasan penolakan yang sudah diketik tidak hilang
        }

        DB::beginTransaction(); // Gunakan transaksi jika ada operasi DB lain yang terkait
        try {
            $pengajuan->status = 'ditolak';

            // SIMPAN ALASAN PENOLAKAN JIKA DIISI
            if ($request->filled('alasan_penolakan')) {
                $pengajuan->alasan_penolakan = $request->alasan_penolakan;
            } else {
                $pengajuan->alasan_penolakan = null; // Pastikan null jika kosong
            }
            $pengajuan->save();

            DB::commit();
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                ->with('success', 'Pengajuan mahasiswa ' . optional($pengajuan->mahasiswa)->nama_lengkap . ' telah DITOLAK.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menolak pengajuan (ID: ' . $pengajuan->pengajuan_id . '): ' . $e->getMessage() . "\nStack: " . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat mencoba menolak pengajuan.');
        }
    }
}
