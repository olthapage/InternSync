<?php
namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanController extends Controller
{
    public function index()
    {
        $activeMenu = 'pengajuan';

        // Ambil ID mahasiswa langsung dari auth()->user()
        $mahasiswaId = auth()->user()->mahasiswa_id;

        $pengajuan = PengajuanModel::with(['lowongan.industri'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->get();

        return view('mahasiswa_page.pengajuan.index', compact('activeMenu', 'pengajuan'));
    }

    public function create()
    {
        $activeMenu = 'pengajuan';

        $lowonganList = DetailLowonganModel::with('industri')->get();
        $industriList = IndustriModel::all();

        return view('mahasiswa_page.pengajuan.create', compact('activeMenu', 'lowonganList', 'industriList'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Pastikan user memiliki relasi mahasiswa
        if (! $user->mahasiswa) {
            return redirect()->back()->with('error', 'Akun ini tidak terhubung ke data mahasiswa.');
        }

        $mahasiswa = $user->mahasiswa;

        // Daftar kolom yang WAJIB terisi untuk bisa mengajukan
        $requiredFields = [
            'nama_lengkap', 'email', 'nim', 'status', 'ipk',
            'level_id', 'prodi_id', 'dosen_id',
            'sertifikat_kompetensi', 'pakta_integritas', 'daftar_riwayat_hidup',
            'khs', 'ktp', 'ktm', 'surat_izin_ortu', 'proposal',
        ];

        foreach ($requiredFields as $field) {
            if (empty($mahasiswa->$field)) {
                return redirect()->back()->with('error', 'Gagal mengajukan. Data profil belum lengkap. Silakan lengkapi semua informasi yang diperlukan.');
            }
        }

        // Validasi form input
        $validator = Validator::make($request->all(), [
            'lowongan_id'     => 'required|exists:m_detail_lowongan,lowongan_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan pengajuan
        PengajuanModel::create([
            'mahasiswa_id'    => $mahasiswa->mahasiswa_id,
            'lowongan_id'     => $request->lowongan_id,
            'tanggal_mulai'   => Carbon::parse($request->tanggal_mulai),
            'tanggal_selesai' => Carbon::parse($request->tanggal_selesai),
            'status'          => 'pending',
        ]);

        return redirect()->route('mahasiswa.pengajuan.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function show(Request $request, $id)
    {
        $pengajuan = PengajuanModel::with(['mahasiswa', 'lowongan'])->find($id);

        if ($request->ajax()) {
            return view('admin_page.pengajuan.show', compact('pengajuan'));
        }
    }

    public function edit(Request $request, $id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $mahasiswa = MahasiswaModel::all();
        $lowongan  = DetailLowonganModel::all();

        if ($request->ajax()) {
            return view('admin_page.pengajuan.edit', compact('pengajuan', 'mahasiswa', 'lowongan'));
        }

        $activeMenu = 'pengajuan';
        return view('admin_page.pengajuan.edit', compact('pengajuan', 'mahasiswa', 'lowongan', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id'      => 'required',
                'lowongan_id'       => 'required',
                'tanggal_pengajuan' => 'required|date',
                'status'            => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $pengajuan = PengajuanModel::find($id);
            if ($pengajuan) {
                $pengajuan->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Pengajuan berhasil diperbarui',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    public function deleteModal(Request $request, $id)
    {
        $pengajuan = PengajuanModel::with(['mahasiswa', 'lowongan'])->find($id);
        return view('admin_page.pengajuan.delete', compact('pengajuan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('pengajuan.index');
        }

        $pengajuan = PengajuanModel::find($id);
        if ($pengajuan) {
            $pengajuan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Pengajuan berhasil dihapus',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Data tidak ditemukan',
        ]);
    }
}
