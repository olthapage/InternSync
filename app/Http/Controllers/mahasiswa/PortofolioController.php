<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MahasiswaModel;
use App\Models\DetailSkillModel;
use App\Models\MahasiswaSkillModel;
use App\Models\PortofolioMahasiswa;
// PortofolioSkill model tidak perlu di-use jika menggunakan attach/sync/detach pada relasi BelongsToMany

class PortofolioController extends Controller
{
    /**
     * Menampilkan halaman manajemen skill dan portofolio mahasiswa.
     */
    public function index()
    {
        $mahasiswa = Auth::user(); // Asumsi Auth::user() adalah instance MahasiswaModel
        if (!$mahasiswa || !($mahasiswa instanceof MahasiswaModel)) {
            // Handle jika user bukan mahasiswa atau tidak terautentikasi dengan benar
            return redirect()->route('login')->with('error', 'Silakan login sebagai mahasiswa.');
        }

        $claimedSkills = MahasiswaSkillModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->with('detailSkill', 'linkedPortofolios') // Eager load detail skill dan portofolio terkait
            ->orderBy('created_at', 'desc')
            ->get();

        $portfolioItems = PortofolioMahasiswa::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->with('linkedMahasiswaSkills.detailSkill') // Eager load skill yg terhubung ke portofolio ini
            ->orderBy('created_at', 'desc')
            ->get();

        $availableSkills = DetailSkillModel::orderBy('skill_nama')->get();
        $activeMenu = 'portofolio'; // Untuk navigasi aktif jika ada

        return view('mahasiswa_page.portofolio.index', compact(
            'mahasiswa',
            'claimedSkills',
            'portfolioItems',
            'availableSkills',
            'activeMenu'
        ));
    }

    /**
     * Menyimpan skill baru yang diklaim mahasiswa.
     */
    public function storeSkill(Request $request)
    {
        $mahasiswa = Auth::user();
        $validator = Validator::make($request->all(), [
            'skill_id' => 'required|exists:m_detail_skill,skill_id',
            'level_kompetensi' => 'required|string|in:Beginner,Intermediate,Expert',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mahasiswa.portofolio.index')
                ->withErrors($validator, 'storeSkillErrors')
                ->withInput();
        }

        // Cek duplikasi
        $existingSkill = MahasiswaSkillModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('skill_id', $request->skill_id)
            ->first();

        if ($existingSkill) {
            return redirect()->route('mahasiswa.portofolio.index')
                ->with('error', 'Skill tersebut sudah Anda tambahkan sebelumnya.');
        }

        MahasiswaSkillModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'skill_id' => $request->skill_id,
            'level_kompetensi' => $request->level_kompetensi,
            'status_verifikasi' => 'Pending',
        ]);

        return redirect()->route('mahasiswa.portofolio.index')->with('success', 'Skill berhasil ditambahkan!');
    }

    /**
     * Menghapus skill yang diklaim mahasiswa.
     */
    public function destroySkill(MahasiswaSkillModel $mahasiswaSkill)
    {
        // Pastikan mahasiswa yang login adalah pemilik skill ini
        if ($mahasiswaSkill->mahasiswa_id !== Auth::id()) {
            return redirect()->route('mahasiswa.portofolio.index')->with('error', 'Aksi tidak diizinkan.');
        }
        // Hapus juga link di pivot table (opsional, tergantung onDelete cascade)
        // $mahasiswaSkill->linkedPortofolios()->detach(); // Jika relasi many-to-many didefinisikan
        $mahasiswaSkill->delete();
        return redirect()->route('mahasiswa.portofolio.index')->with('success', 'Skill berhasil dihapus.');
    }

    /**
     * Menyimpan item portofolio baru.
     */
    public function storePortfolio(Request $request)
    {
        $mahasiswa = Auth::user();
        $validator = Validator::make($request->all(), [
            'judul_portofolio' => 'required|string|max:255',
            'deskripsi_portofolio' => 'nullable|string',
            'tipe_portofolio' => 'required|string|in:file,url,gambar,video',
            'lokasi_file_atau_url_input' => 'required_if:tipe_portofolio,url,video|nullable|url|max:500',
            'lokasi_file_upload' => 'required_if:tipe_portofolio,file,gambar|nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:5120', // Max 5MB
            'tanggal_pengerjaan_mulai' => 'nullable|date',
            'tanggal_pengerjaan_selesai' => 'nullable|date|after_or_equal:tanggal_pengerjaan_mulai',
            'linked_mahasiswa_skills'   => 'nullable|array',
            'linked_mahasiswa_skills.*' => 'exists:mahasiswa_skill,mahasiswa_skill_id', // Validasi setiap ID skill mahasiswa
            'deskripsi_penggunaan_skill' => 'nullable|array',
            // Pastikan jumlah deskripsi penggunaan skill (jika ada) sesuai dengan jumlah skill yang di-link
        ]);

        if ($validator->fails()) {
            return redirect()->route('mahasiswa.portofolio.index')
                ->withErrors($validator, 'storePortfolioErrors')
                ->withInput();
        }

        $lokasiFinal = null;
        if ($request->tipe_portofolio === 'url' || $request->tipe_portofolio === 'video') {
            $lokasiFinal = $request->lokasi_file_atau_url_input;
        } elseif (($request->tipe_portofolio === 'file' || $request->tipe_portofolio === 'gambar') && $request->hasFile('lokasi_file_upload')) {
            // Simpan file ke storage/app/public/mahasiswa_portofolio/{mahasiswa_id}/
            // Jangan lupa jalankan `php artisan storage:link`
            $filePath = $request->file('lokasi_file_upload')->store('mahasiswa_portofolio/' . $mahasiswa->mahasiswa_id, 'public');
            $lokasiFinal = $filePath;
        } else {
            // Seharusnya tidak terjadi jika validasi benar, tapi sebagai fallback
            return redirect()->route('mahasiswa.portofolio.index')->with('error', 'File atau URL portofolio wajib diisi sesuai tipe.')->withInput();
        }

        $portfolio = PortofolioMahasiswa::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'judul_portofolio' => $request->judul_portofolio,
            'deskripsi_portofolio' => $request->deskripsi_portofolio,
            'tipe_portofolio' => $request->tipe_portofolio,
            'lokasi_file_atau_url' => $lokasiFinal,
            'tanggal_pengerjaan_mulai' => $request->tanggal_pengerjaan_mulai,
            'tanggal_pengerjaan_selesai' => $request->tanggal_pengerjaan_selesai,
        ]);

        // Link portofolio dengan skills
        if ($request->has('linked_mahasiswa_skills') && $portfolio) {
            $skillsToSync = [];
            foreach ($request->linked_mahasiswa_skills as $index => $mahasiswaSkillId) {
                $deskripsiPenggunaan = $request->deskripsi_penggunaan_skill[$mahasiswaSkillId] ?? null; // Ambil deskripsi berdasarkan mahasiswa_skill_id
                $skillsToSync[$mahasiswaSkillId] = ['deskripsi_penggunaan_skill' => $deskripsiPenggunaan];
            }
            $portfolio->linkedMahasiswaSkills()->sync($skillsToSync);
        }


        return redirect()->route('mahasiswa.portofolio.index')->with('success', 'Portofolio berhasil ditambahkan!');
    }

    /**
     * Menghapus item portofolio.
     */
    public function destroyPortfolio(PortofolioMahasiswa $portfolio)
    {
        // Pastikan mahasiswa yang login adalah pemilik portofolio ini
        if ($portfolio->mahasiswa_id !== Auth::id()) {
            return redirect()->route('mahasiswa.portofolio.index')->with('error', 'Aksi tidak diizinkan.');
        }

        // Hapus file dari storage jika tipe adalah file atau gambar
        if (($portfolio->tipe_portofolio === 'file' || $portfolio->tipe_portofolio === 'gambar') && $portfolio->lokasi_file_atau_url) {
            Storage::disk('public')->delete($portfolio->lokasi_file_atau_url);
        }

        // Detach semua skill yang terhubung (otomatis jika onDelete cascade di DB, tapi lebih baik eksplisit)
        $portfolio->linkedMahasiswaSkills()->detach();
        $portfolio->delete();

        return redirect()->route('mahasiswa.portofolio.index')->with('success', 'Portofolio berhasil dihapus.');
    }

    // Anda bisa menambahkan metode edit/update untuk skill dan portofolio nanti
}
