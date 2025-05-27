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
            'judul_lowongan'              => 'required|string|max:255',
            'kategori_skill_id'           => 'required|exists:m_kategori_skill,kategori_skill_id', // Pastikan nama tabel dan kolom sesuai
            'slot'                        => 'required|integer|min:1',
            'deskripsi'                   => 'required|string',
            'tanggal_mulai'               => 'required|date',
            'tanggal_selesai'             => 'required|date|after_or_equal:tanggal_mulai',
            'pendaftaran_tanggal_mulai'   => 'required|date',
            'pendaftaran_tanggal_selesai' => 'required|date|after_or_equal:pendaftaran_tanggal_mulai',
            'skills'                      => 'sometimes|array',                  // 'skills' adalah array dari skill_id
            'skills.*'                    => 'required|exists:m_detail_skill,skill_id', // Asumsi tabel skill adalah m_skill dan primary key skill_id
            'bobot'                       => 'sometimes|array',
            'bobot.*'                     => 'required|numeric|min:1|max:100', // Asumsi bobot antara 1-100
        ], [
            'kategori_skill_id.required' => 'Kategori lowongan wajib dipilih.',
            'kategori_skill_id.exists'   => 'Kategori lowongan tidak valid.',
            'skills.*.exists'            => 'Salah satu skill yang dipilih tidak valid.',
            'bobot.*.required'           => 'Bobot untuk setiap skill wajib diisi.',
            'bobot.*.numeric'            => 'Bobot harus berupa angka.',
            'bobot.*.min'                => 'Bobot minimal adalah 1.',
            'bobot.*.max'                => 'Bobot maksimal adalah 100.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Pastikan jumlah skills dan bobot cocok jika ada
        if ($request->has('skills') && $request->has('bobot')) {
            if (count($request->input('skills')) !== count($request->input('bobot'))) {
                return redirect()->back()
                    ->withErrors(['skills' => 'Jumlah skill dan bobot tidak cocok.'])
                    ->withInput();
            }
        }

        DB::beginTransaction();
        try {
                                      // Ambil industri_id dari user yang sedang login
                                      // Sesuaikan dengan implementasi Auth Anda
            $industriId = Auth::id(); // Jika primary key user adalah industri_id
                                      // atau $industriId = Auth::user()->industri_id; // Jika user memiliki relasi atau properti industri_id

            if (! $industriId) {
                throw new \Exception("Tidak dapat menemukan ID Industri yang terautentikasi.");
            }

            $lowongan = DetailLowonganModel::create([
                'judul_lowongan'              => $request->judul_lowongan,
                'kategori_skill_id'           => $request->kategori_skill_id,
                'slot'                        => $request->slot,
                'deskripsi'                   => $request->deskripsi,
                'tanggal_mulai'               => $request->tanggal_mulai,
                'tanggal_selesai'             => $request->tanggal_selesai,
                'pendaftaran_tanggal_mulai'   => $request->pendaftaran_tanggal_mulai,
                'pendaftaran_tanggal_selesai' => $request->pendaftaran_tanggal_selesai,
                'industri_id'                 => $industriId,
            ]);

            if ($request->has('skills')) {
                foreach ($request->input('skills') as $index => $skillId) {
                    if (! empty($skillId) && isset($request->input('bobot')[$index])) {
                        LowonganSkillModel::create([
                            'lowongan_id' => $lowongan->lowongan_id,
                            'skill_id'    => $skillId,
                            'bobot'       => $request->input('bobot')[$index],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('industri.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error $e->getMessage()
            return redirect()->back()->with('error', 'Gagal menambahkan lowongan: ' . $e->getMessage())->withInput();
        }
    }
}
