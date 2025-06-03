<?php
namespace App\Http\Controllers\industri;

use App\Models\KotaModel;
use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\ProvinsiModel;
use App\Models\PengajuanModel;
use App\Models\DetailSkillModel;
use App\Services\SpkEdasService;
use App\Models\KategoriSkillModel;
use App\Models\LowonganSkillModel;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $activeMenu = 'lowongan';
        $loggedInIndustri = Auth::guard('industri')->user(); // Gunakan guard eksplisit

        if (!$loggedInIndustri) {
            return redirect()->route('login.company.view') // Arahkan ke login industri
                ->with('error', 'Sesi tidak valid. Silakan login kembali sebagai industri.');
        }
        $authenticatedIndustriId = $loggedInIndustri->industri_id; // Asumsi PK IndustriModel adalah industri_id

        if (optional($pengajuan->lowongan)->industri_id !== $authenticatedIndustriId) {
            return redirect()->route('industri.lowongan.index')
                ->with('error', 'Anda tidak berhak mengakses data pendaftar ini.');
        }

        $pengajuan->load([
            'mahasiswa' => function ($query) {
                $query->with([
                    'prodi',
                    'skills' => function ($skillQuery) {
                        $skillQuery->with(['detailSkill.kategori', 'linkedPortofolios'])
                                   // ->where('status_verifikasi', 'Valid'); // Biarkan DPA yang menilai ini, industri lihat semua
                                   ;
                    },
                ]);
            },
            'lowongan' => function ($query) {
                $query->with(['lowonganSkill.skill', 'kategoriSkill', 'industri']); // Muat industri juga di sini
            }
        ]);

        $allPortfolioItems = optional($pengajuan->mahasiswa)->portofolios()->orderBy('created_at', 'desc')->get() ?? collect();

        return view('industri_page.lowongan.pendaftar', compact( // Pastikan path view benar
            'pengajuan',
            'allPortfolioItems',
            'activeMenu'
        ));
    }


    public function terimaPengajuan(Request $request, PengajuanModel $pengajuan)
    {
        $loggedInIndustri = Auth::guard('industri')->user();
        if (!$loggedInIndustri) {
            return redirect()->back()->with('error', 'Sesi tidak valid.');
        }
        $authenticatedIndustriId = $loggedInIndustri->industri_id;

        if ($pengajuan->lowongan->industri_id !== $authenticatedIndustriId) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan untuk pengajuan ini.');
        }

        if (strtolower($pengajuan->status) !== 'belum') {
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('warning', 'Pengajuan ini sudah diproses sebelumnya (' . ucfirst($pengajuan->status) . ').');
        }

        if ($pengajuan->lowongan->slotTersedia() <= 0) { // Pastikan slotTersedia() benar
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('error', 'Slot untuk lowongan ini sudah penuh.');
        }

        DB::beginTransaction();
        try {
            $pengajuan->status = 'diterima';
            $pengajuan->save();

            MagangModel::create([
                'mahasiswa_id' => $pengajuan->mahasiswa_id,
                'lowongan_id'  => $pengajuan->lowongan_id,
                'status'       => 'belum', // Status awal di MagangModel
            ]);

            DB::commit();
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('success', 'Pengajuan mahasiswa ' . optional($pengajuan->mahasiswa)->nama_lengkap . ' berhasil DITERIMA.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menerima pengajuan (ID: ' . $pengajuan->pengajuan_id . '): ' . $e->getMessage() . "\nStack: " . $e->getTraceAsString());
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('error', 'Terjadi kesalahan sistem saat mencoba menerima pengajuan.');
        }
    }

    public function tolakPengajuan(Request $request, PengajuanModel $pengajuan)
    {
        $loggedInIndustri = Auth::guard('industri')->user();
        if (!$loggedInIndustri) {
            return redirect()->back()->with('error', 'Sesi tidak valid.');
        }
        $authenticatedIndustriId = $loggedInIndustri->industri_id;

        if ($pengajuan->lowongan->industri_id !== $authenticatedIndustriId) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        if (strtolower($pengajuan->status) !== 'belum') {
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('warning', 'Pengajuan ini sudah diproses sebelumnya (' . ucfirst($pengajuan->status) . ').');
        }

        $validator = Validator::make($request->all(), [
            'alasan_penolakan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                ->withErrors($validator)
                ->withInput()
                ->with('error_form_tolak_pengajuan_id', $pengajuan->pengajuan_id); // Agar bisa highlight form yg error
        }

        DB::beginTransaction();
        try {
            $pengajuan->status = 'ditolak';
            if ($request->filled('alasan_penolakan')) {
               $pengajuan->alasan_penolakan = $request->alasan_penolakan;
            } else {
               $pengajuan->alasan_penolakan = null;
            }
            $pengajuan->save();

            DB::commit();
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('success', 'Pengajuan mahasiswa ' . optional($pengajuan->mahasiswa)->nama_lengkap . ' telah DITOLAK.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menolak pengajuan (ID: ' . $pengajuan->pengajuan_id . '): ' . $e->getMessage());
            return redirect()->route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id)
                             ->with('error', 'Terjadi kesalahan sistem.');
        }
    }
    public function getSpkModalKriteriaForm(DetailLowonganModel $lowongan)
    {
        $lowongan->load('lowonganSkill.skill');
        return view('industri_page.lowongan.partials.rekomendasi_kriteria_form', compact('lowongan'));
    }

    public function calculateSpkRekomendasi(Request $request, DetailLowonganModel $lowongan, SpkEdasService $spkService)
    {
        $rules = [];
        $messages = [];

        // Validasi untuk bobot skill
        if ($request->has('bobot_skill')) {
            foreach ($request->input('bobot_skill') as $skillId => $bobot) {
                $rules['bobot_skill.' . $skillId] = 'required|numeric|min:0|max:100';
                $messages['bobot_skill.' . $skillId . '.required'] = 'Bobot untuk skill wajib diisi.';
                $messages['bobot_skill.' . $skillId . '.numeric'] = 'Bobot skill harus angka.';
                $messages['bobot_skill.' . $skillId . '.min'] = 'Bobot skill min 0.';
                $messages['bobot_skill.' . $skillId . '.max'] = 'Bobot skill maks 100.';
            }
        }

        // Validasi untuk kriteria tambahan jika checkbox-nya dicentang
        $criteriaTambahan = ['ipk', 'organisasi', 'lomba', 'skor_ais', 'kasus'];
        $criteriaTambahanLabels = [
            'ipk' => 'IPK',
            'organisasi' => 'Aktivitas Organisasi',
            'lomba' => 'Aktivitas Lomba',
            'skor_ais' => 'Skor AIS',
            'kasus' => 'Status Kasus'
        ];

        foreach ($criteriaTambahan as $kriteria) {
            if ($request->boolean('gunakan_' . $kriteria)) {
                $rules['bobot_' . $kriteria] = 'required|numeric|min:0|max:100';
                $messages['bobot_' . $kriteria . '.required'] = 'Bobot untuk ' . $criteriaTambahanLabels[$kriteria] . ' wajib diisi jika kriteria ini digunakan.';
                $messages['bobot_' . $kriteria . '.numeric'] = 'Bobot ' . $criteriaTambahanLabels[$kriteria] . ' harus angka.';
                $messages['bobot_' . $kriteria . '.min'] = 'Bobot ' . $criteriaTambahanLabels[$kriteria] . ' minimal 0.';
                $messages['bobot_' . $kriteria . '.max'] = 'Bobot ' . $criteriaTambahanLabels[$kriteria] . ' maksimal 100.';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['message' => 'Input tidak valid. Periksa kembali semua bobot yang Anda masukkan.', 'errors' => $validator->errors()], 422);
        }

        // Kumpulkan bobot kriteria dari request
        $criteriaWeights = [
            'bobot_skill' => $request->input('bobot_skill', []),
        ];
        foreach ($criteriaTambahan as $kriteria) {
            $criteriaWeights['gunakan_' . $kriteria] = $request->boolean('gunakan_' . $kriteria);
            $criteriaWeights['bobot_' . $kriteria]   = $request->input('bobot_' . $kriteria, 0);
        }

        // Ambil pendaftar yang relevan
        $pendaftar = $lowongan->pendaftar()
            ->where('status', 'belum') // Hanya pendaftar dengan status 'belum'
            ->with([
                'mahasiswa.skills' => function($query) { // Eager load skills mahasiswa
                    $query->where('status_verifikasi', 'Valid'); // Hanya skill yang valid
                },
                'mahasiswa.prodi'
            ])
            ->get();

        if ($pendaftar->isEmpty()) {
             $data = ['rankedMahasiswa' => collect(), 'criteriaView' => [], 'message' => 'Tidak ada pendaftar dengan status "belum" yang memenuhi syarat untuk dievaluasi pada lowongan ini.'];
             return view('industri_page.lowongan.partials.rekomendasi_hasil', $data);
        }

        try {
        $result = $spkService->calculateRekomendasi($lowongan, $pendaftar, $criteriaWeights);

        // Tambahkan $lowongan ke array $result sebelum dikirim ke view
        $result['lowongan'] = $lowongan;

        return view('industri_page.lowongan.partials.rekomendasi_hasil', $result);
    } catch (\Exception $e) {
        Log::error('SPK EDAS Calculation Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        // Saat error, mungkin juga perlu mengirim $lowongan jika view membutuhkannya untuk ID modal
        // return response()->json(['message' => 'Terjadi kesalahan internal saat menghitung rekomendasi: ' . $e->getMessage()], 500);
        // Jika mengembalikan view error:
        return view('industri_page.lowongan.partials.rekomendasi_hasil', [
            'error_message' => 'Terjadi kesalahan internal: ' . $e->getMessage(),
            'lowongan' => $lowongan // Kirim $lowongan agar ID modal tetap benar
        ]);
    }
    }
    public function getSpkLangkahEdas(Request $request, DetailLowonganModel $lowongan, SpkEdasService $spkService)
    {
        Log::info('Memulai getSpkLangkahEdas untuk Lowongan ID: ' . $lowongan->lowongan_id);
        Log::debug('Request data untuk getSpkLangkahEdas:', $request->all());

        // Validasi input bobot (sama seperti di calculateSpkRekomendasi)
        $skillRules = [];
        if ($request->has('bobot_skill')) {
            foreach ($request->input('bobot_skill') as $skillId => $bobot) {
                $skillRules['bobot_skill.' . $skillId] = 'required|numeric|min:0|max:100';
            }
        }
        // Inisialisasi rules untuk kriteria tambahan
        $additionalCriteriaRules = [];
        $criteriaTambahan = ['ipk', 'organisasi', 'lomba', 'skor_ais', 'kasus'];
        foreach ($criteriaTambahan as $kriteria) {
            if ($request->boolean('gunakan_' . $kriteria)) {
                $additionalCriteriaRules['bobot_' . $kriteria] = 'required|numeric|min:0|max:100';
            }
        }

        $validator = Validator::make($request->all(), array_merge($skillRules, $additionalCriteriaRules));

        if ($validator->fails()) {
            Log::warning('Validasi gagal untuk getSpkLangkahEdas Lowongan ID: ' . $lowongan->lowongan_id, $validator->errors()->toArray());
            // Untuk AJAX, kembalikan JSON error agar bisa ditangani di front-end
            return response()->json(['message' => 'Input bobot tidak valid.', 'errors' => $validator->errors()], 422);
        }

        $criteriaWeights = [
            'bobot_skill' => $request->input('bobot_skill', []),
        ];
        foreach ($criteriaTambahan as $kriteria) {
            $criteriaWeights['gunakan_' . $kriteria] = $request->boolean('gunakan_' . $kriteria);
            // Ambil bobot hanya jika kriteria digunakan, jika tidak, service harus handle default atau mengabaikannya
            $criteriaWeights['bobot_' . $kriteria]   = $request->boolean('gunakan_' . $kriteria) ? $request->input('bobot_' . $kriteria, 0) : 0;
        }
        Log::debug('CriteriaWeights yang dikirim ke service:', $criteriaWeights);

        $pendaftar = $lowongan->pendaftar()
            ->where('status', 'belum') // Sesuaikan status ini jika perlu
            ->with([
                'mahasiswa.skills' => function($query) {
                    $query->with('detailSkill')->where('status_verifikasi', 'Valid'); // Hanya skill valid
                },
                'mahasiswa.prodi'
            ])
            ->get();

        Log::info('Jumlah pendaftar yang akan diproses untuk langkah EDAS: ' . $pendaftar->count(), ['lowongan_id' => $lowongan->lowongan_id]);

        if ($pendaftar->isEmpty()) {
            return response()->view('industri_page.lowongan.partials.rekomendasi_langkah_edas', [
                'error_message' => 'Tidak ada pendaftar dengan status "belum" untuk ditampilkan langkah perhitungannya.'
            ]);
        }

        try {
            Log::info('Memanggil SpkEdasService->getEdasCalculationSteps', ['lowongan_id' => $lowongan->lowongan_id]);
            $edasSteps = $spkService->getEdasCalculationSteps($lowongan, $pendaftar, $criteriaWeights);

            if(isset($edasSteps['error_message'])){
                Log::warning('Error dari SpkEdasService saat getEdasCalculationSteps:', ['error' => $edasSteps['error_message'], 'lowongan_id' => $lowongan->lowongan_id]);
                 return response()->view('industri_page.lowongan.partials.rekomendasi_langkah_edas', ['error_message' => $edasSteps['error_message']]);
            }

            Log::info('Berhasil mendapatkan langkah EDAS, merender view.', ['lowongan_id' => $lowongan->lowongan_id]);
            return view('industri_page.lowongan.partials.rekomendasi_langkah_edas', $edasSteps);

        } catch (\Exception $e) {
            Log::error('EXCEPTION di getSpkLangkahEdas untuk Lowongan ID ' . $lowongan->lowongan_id . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            // Mengembalikan view dengan pesan error agar bisa ditampilkan di modal
            return response()->view('industri_page.lowongan.partials.rekomendasi_langkah_edas', ['error_message' => 'Terjadi kesalahan internal saat memuat langkah perhitungan. Silakan coba lagi atau hubungi administrator.']);
        }
    }
}
