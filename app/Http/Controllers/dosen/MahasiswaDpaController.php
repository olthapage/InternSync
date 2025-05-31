<?php

namespace App\Http\Controllers\dosen; // Pastikan namespace ini benar

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use App\Models\DosenModel; // Untuk mendapatkan info DPA yang login
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\MahasiswaSkillModel;

class MahasiswaDpaController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen mahasiswa untuk DPA.
     */
    public function index()
    {
        $activeMenu = 'mahasiswa-dpa'; // Untuk menandai menu aktif di sidebar
        $dpa = Auth::user(); // Mendapatkan data DPA yang sedang login (instance DosenModel)
        $prodiName = $dpa->prodi->nama_prodi ?? 'Semua Prodi'; // Mendapatkan nama prodi DPA
        $activeMenu = 'validasi-portofolio';

        // Jika DPA tidak memiliki prodi_id, Anda bisa memutuskan apa yang ditampilkan.
        // Misalnya, pesan bahwa mereka perlu di-assign ke prodi.
        if (is_null($dpa->prodi_id)) {
            // Anda bisa set $prodiName menjadi pesan khusus atau handle di view
            $prodiName = 'Tidak terasosiasi dengan Program Studi tertentu';
        }

        return view('dosen_page.mahasiswa_dpa.index', compact('activeMenu', 'dpa', 'prodiName', 'activeMenu'));
    }

    /**
     * Menyediakan data untuk DataTables daftar mahasiswa.
     */
    public function list(Request $request)
    {
        $dpa = Auth::user(); // DosenModel instance

        // Jika DPA tidak memiliki prodi_id, kembalikan data kosong atau sesuai kebijakan.
        // Untuk contoh ini, kita akan tampilkan mahasiswa yang prodinya sama dengan DPA.
        // Jika DPA tidak punya prodi_id, maka tidak ada mahasiswa yang ditampilkan berdasarkan filter ini.
        if (is_null($dpa->prodi_id)) {
            return DataTables::of(collect([])) // Mengembalikan koleksi kosong
                ->addIndexColumn()
                ->addColumn('nama_lengkap_mahasiswa', function ($row) { return ''; }) // Kolom dummy
                ->addColumn('prodi_mahasiswa', function ($row) { return ''; })
                ->addColumn('skill_pending_count', function ($row) { return ''; })
                ->addColumn('aksi', function ($row) { return ''; })
                ->make(true);
        }

        // Ambil mahasiswa yang memiliki prodi_id sama dengan prodi_id DPA
        // dan eager load relasi yang dibutuhkan
        $query = MahasiswaModel::with([
                'prodi', // Untuk menampilkan nama prodi mahasiswa
                'skills' => function ($q_skill) { // Untuk menghitung skill yang statusnya 'Pending'
                    $q_skill->where('status_verifikasi', 'Pending');
                }
            ])
            ->where('prodi_id', $dpa->prodi_id)
            ->select('m_mahasiswa.*'); // Pastikan memilih semua kolom dari tabel utama

        return DataTables::of($query)
            ->addIndexColumn() // Menambahkan kolom nomor urut DT_RowIndex
            ->addColumn('nama_lengkap_mahasiswa', function ($row) {
                $foto = $row->foto ? asset('storage/foto/' . $row->foto) : asset('assets/default-profile.png'); // Sesuaikan path foto default
                return '
                <div class="d-flex align-items-center">
                    <img src="' . htmlspecialchars($foto) . '" class="avatar avatar-sm me-3 rounded-circle" alt="foto_mahasiswa">
                    <div>
                        <h6 class="mb-0 text-sm">' . htmlspecialchars($row->nama_lengkap) . '</h6>
                        <p class="text-xs text-secondary mb-0">NIM: ' . htmlspecialchars($row->nim) . '</p>
                    </div>
                </div>';
            })
            ->addColumn('prodi_mahasiswa', function ($row) {
                return $row->prodi->nama_prodi ?? '-';
            })
            ->addColumn('skill_pending_count', function ($row) {
                $count = $row->skills->count(); // Mengambil hasil count dari eager loading dengan kondisi
                if ($count > 0) {
                    return '<span class="badge bg-warning text-dark">' . $count . ' Skill Menunggu Verifikasi</span>';
                }
                return '<span class="badge bg-success">Semua Skill Terverifikasi</span>';
            })
            ->addColumn('aksi', function ($row) {
                $validasiUrl = route('dosen.mahasiswa-dpa.validasi.skill.show', $row->mahasiswa_id);
                return '<a href="' . $validasiUrl . '" class="btn btn-sm btn-primary"><i class="fas fa-user-shield me-1"></i> Validasi Skill</a>';
            })
            ->rawColumns(['nama_lengkap_mahasiswa', 'skill_pending_count', 'aksi'])
            ->make(true);
    }
    public function showValidasiSkillPage(MahasiswaModel $mahasiswa)
    {
        $dpa = Auth::user();
        $activeMenu = 'mahasiswa-dpa';

        // Autorisasi: Pastikan DPA hanya bisa mengakses mahasiswa dari prodinya
        if (is_null($dpa->prodi_id) || $mahasiswa->prodi_id !== $dpa->prodi_id) {
            // Atau jika DPA tidak punya prodi_id sama sekali
            return redirect()->route('dosen.mahasiswa-dpa.index')->with('error', 'Akses ditolak atau mahasiswa tidak ditemukan dalam prodi Anda.');
        }

        $skillsForValidation = MahasiswaSkillModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->with(['detailSkill', 'linkedPortofolios.portofolio']) // Eager load portofolio yang terhubung
            ->orderBy('created_at', 'asc') // Urutkan agar lebih mudah direview
            ->get();

        return view('dosen_page.mahasiswa_dpa.validasi_skill_show', compact(
            'mahasiswa',
            'skillsForValidation',
            'dpa',
            'activeMenu'
        ));
    }
    public function updateSkillValidation(Request $request, MahasiswaSkillModel $mahasiswaSkill)
    {
        $dpa = Auth::user();

        // Autorisasi: Pastikan DPA hanya bisa memvalidasi skill mahasiswa dari prodinya
        // dan mahasiswaSkill yang diupdate memang milik mahasiswa di prodi DPA
        $mahasiswaTerkait = $mahasiswaSkill->mahasiswa; // Akses relasi mahasiswa dari mahasiswaSkill
        if (is_null($dpa->prodi_id) || is_null($mahasiswaTerkait) || $mahasiswaTerkait->prodi_id !== $dpa->prodi_id) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        $validator = Validator::make($request->all(), [
            'status_verifikasi' => 'required|string|in:Pending,Valid,Invalid',
            'level_kompetensi' => 'required|string|in:Beginner,Intermediate,Expert',
            // 'catatan_dpa' => 'nullable|string|max:500' // Jika ada field catatan
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_skill_id', $mahasiswaSkill->mahasiswa_skill_id); // Untuk fokus ke form yg error jika ada
        }

        $mahasiswaSkill->status_verifikasi = $request->status_verifikasi;
        $mahasiswaSkill->level_kompetensi = $request->level_kompetensi; // DPA bisa menyesuaikan level
        // $mahasiswaSkill->catatan_dpa = $request->catatan_dpa; // Jika ada
        $mahasiswaSkill->save();

        return redirect()->route('dosen.mahasiswa-dpa.validasi.skill.show', $mahasiswaSkill->mahasiswa_id)
            ->with('success', 'Status verifikasi dan level untuk skill "' . ($mahasiswaSkill->detailSkill->skill_nama ?? '') . '" berhasil diperbarui.');
    }
}
