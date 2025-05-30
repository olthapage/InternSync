<?php
namespace App\Http\Controllers\industri;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\DetailSkillModel;
use App\Models\KategoriSkillModel;
use App\Models\LowonganSkillModel;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Ditambahkan untuk Request jika diperlukan
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
        // Ambil semua Kategori Skill untuk dropdown
        // Asumsi KategoriSkillModel memiliki 'kategori_skill_id' dan 'nama_kategori_skill'
        $kategoriSkills = KategoriSkillModel::orderBy('kategori_nama')->get();

        // Ambil semua Skill Detail untuk pilihan skill
        // Asumsi DetailSkillModel memiliki 'skill_id' dan 'nama_skill'
        $detailSkills = DetailSkillModel::orderBy('skill_nama')->get();

                                  // Ambil data industri yang sedang login
                                  // Sesuaikan cara Anda mendapatkan industri_id, ini contoh jika user adalah IndustriModel
        $industri = Auth::user(); // Atau cara lain yang sesuai dengan sistem auth Anda
        $activeMenu = 'lowongan';

        if (! $industri || ! isset($industri->industri_id)) {
            // Handle jika user bukan industri atau tidak memiliki industri_id
            // Mungkin redirect atau tampilkan error
            return redirect()->route('industri.lowongan.index')->with('error', 'Akses tidak sah atau data industri tidak ditemukan.');
        }

        return view('industri_page.lowongan.create', compact('kategoriSkills', 'detailSkills', 'industri', 'activeMenu'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_lowongan'            => 'required|string|max:255',
            'kategori_skill_id'         => 'required|exists:m_kategori_skill,kategori_skill_id',
            'slot'                      => 'required|integer|min:1',
            'deskripsi'                 => 'required|string',
            'tanggal_mulai'             => 'required|date',
            'tanggal_selesai'           => 'required|date|after_or_equal:tanggal_mulai',
            'pendaftaran_tanggal_mulai' => 'required|date',
            'pendaftaran_tanggal_selesai' => 'required|date|after_or_equal:pendaftaran_tanggal_mulai',

            // Validasi untuk alamat (jika Anda sudah menambahkannya di form)
            // 'provinsi_id' => 'required|exists:provinsis,id', // Sesuaikan nama tabel dan kolom
            // 'kota_id' => 'required|exists:kotas,id', // Sesuaikan
            // 'alamat_lengkap' => 'required|string|max:500',

            'skills'                    => 'sometimes|array',
            'skills.*'                  => 'required_with:skills|exists:m_detail_skill,skill_id',
            'levels'                    => 'sometimes|array',
            'levels.*'                  => 'required_with:skills|string|in:Beginner,Intermediate,Expert', // Validasi untuk level

            // Validasi untuk bobot kriteria lainnya (IPK & Lokasi)
            'bobot_akademik'            => 'required|numeric|min:1|max:100',
            'bobot_lokasi'              => 'required|numeric|min:1|max:100',

        ], [
            'kategori_skill_id.required' => 'Kategori lowongan wajib dipilih.',
            'kategori_skill_id.exists'   => 'Kategori lowongan tidak valid.',
            'skills.*.exists'            => 'Salah satu skill yang dipilih tidak valid.',
            'levels.*.required_with'     => 'Level kompetensi untuk setiap skill wajib dipilih.',
            'levels.*.in'                => 'Level kompetensi tidak valid.',
            'bobot_akademik.required'    => 'Bobot nilai akademik wajib diisi.',
            'bobot_lokasi.required'      => 'Bobot lokasi wajib diisi.',
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
            $industriId = null;

            if ($loggedInUser instanceof IndustriModel) {
                $industriId = $loggedInUser->industri_id; // Atau $loggedInUser->getKey() jika PKnya adalah industri_id
            } elseif (method_exists($loggedInUser, 'industri')) { // Jika ada relasi 'industri' di model User standar
                $industriRelasi = $loggedInUser->industri; // Misal relasi HasOne atau BelongsTo ke IndustriModel
                if ($industriRelasi) {
                    $industriId = $industriRelasi->industri_id; // Atau $industriRelasi->getKey()
                }
            } else if (isset($loggedInUser->industri_id)) { // Jika ada properti industri_id langsung
                $industriId = $loggedInUser->industri_id;
            }


            if (!$industriId) {
                throw new \Exception("Tidak dapat menemukan ID Industri yang terautentikasi atau user bukan merupakan industri.");
            }

            $lowonganData = [
                'judul_lowongan'            => $request->judul_lowongan,
                'kategori_skill_id'         => $request->kategori_skill_id,
                'slot'                      => $request->slot,
                'deskripsi'                 => $request->deskripsi,
                'tanggal_mulai'             => $request->tanggal_mulai,
                'tanggal_selesai'           => $request->tanggal_selesai,
                'pendaftaran_tanggal_mulai' => $request->pendaftaran_tanggal_mulai,
                'pendaftaran_tanggal_selesai' => $request->pendaftaran_tanggal_selesai,
                'industri_id'               => $industriId,
                // Tambahkan field alamat jika sudah ada di DetailLowonganModel dan form
                // 'provinsi_id' => $request->provinsi_id,
                // 'kota_id' => $request->kota_id,
                // 'alamat_lengkap' => $request->alamat_lengkap,

                // Simpan bobot kriteria lainnya jika ada di DetailLowonganModel
                // atau tabel terpisah (KriteriaMagangModel yang Anda sebutkan sebelumnya)
                // Contoh jika disimpan di DetailLowonganModel (pastikan ada kolomnya):
                // 'bobot_akademik' => $request->bobot_akademik,
                // 'bobot_lokasi' => $request->bobot_lokasi,
            ];

            // Cek apakah model DetailLowonganModel memiliki fillable untuk bobot_akademik dan bobot_lokasi
            // Jika tidak, Anda perlu menyimpannya ke tabel KriteriaMagangModel
            // Untuk saat ini, kita asumsikan belum disimpan langsung di DetailLowonganModel

            $lowongan = DetailLowonganModel::create($lowonganData);

            // Simpan bobot ke tabel kriteria_magang jika KriteriaMagangModel ada
            // Asumsi KriteriaMagangModel memiliki lowongan_id, nama_kriteria, bobot
            if (class_exists(\App\Models\KriteriaMagangModel::class)) {
                 \App\Models\KriteriaMagangModel::updateOrCreate(
                    ['lowongan_id' => $lowongan->lowongan_id, 'nama_kriteria' => 'Akademik (IPK)'],
                    ['bobot' => $request->bobot_akademik]
                );
                \App\Models\KriteriaMagangModel::updateOrCreate(
                    ['lowongan_id' => $lowongan->lowongan_id, 'nama_kriteria' => 'Lokasi'],
                    ['bobot' => $request->bobot_lokasi]
                );
            }


            if ($request->has('skills')) {
                foreach ($request->input('skills') as $index => $skillId) {
                    $levelKompetensi = $request->input('levels')[$index] ?? 'Beginner'; // default jika tidak ada
                    $bobotNumerik = 0;

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

                    if (!empty($skillId)) {
                        LowonganSkillModel::create([
                            'lowongan_id'       => $lowongan->lowongan_id,
                            'skill_id'          => $skillId,
                            'level_kompetensi'  => $levelKompetensi,
                            'bobot'             => $bobotNumerik,
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
}
