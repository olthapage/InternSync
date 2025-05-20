<?php
namespace App\Http\Controllers\mahasiswa;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use Illuminate\Support\Carbon;
use App\Models\KategoriSkillModel;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
        $kategoriSkillList = KategoriSkillModel::all();

        return view('mahasiswa_page.pengajuan.create', compact('activeMenu', 'lowonganList', 'industriList', 'kategoriSkillList'));
    }

    public function store(Request $request)
    {
        // Log awal proses
        Log::info('Memulai proses pengajuan dengan data: ', $request->all());

        // Karena MahasiswaModel adalah Authenticatable, langsung gunakan auth()->user()
        $mahasiswa = auth()->user();

        if (!$mahasiswa) {
            Log::error('Pengajuan gagal: User tidak terautentikasi');
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        Log::info('Proses pengajuan dimulai oleh mahasiswa_id: ' . $mahasiswa->mahasiswa_id);

        // Validasi form input terlebih dahulu
        $validator = Validator::make($request->all(), [
            'lowongan_id'     => 'required|exists:m_detail_lowongan,lowongan_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi form gagal: ' . json_encode($validator->errors()));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan pengajuan
        try {
            $pengajuan = new PengajuanModel();
            $pengajuan->mahasiswa_id = $mahasiswa->mahasiswa_id;
            $pengajuan->lowongan_id = $request->lowongan_id;
            $pengajuan->tanggal_mulai = Carbon::parse($request->tanggal_mulai);
            $pengajuan->tanggal_selesai = Carbon::parse($request->tanggal_selesai);
            $pengajuan->status = 'belum'; // default sesuai enum

            // Log data sebelum disimpan
            Log::info('Data yang akan disimpan: ', $pengajuan->toArray());

            $saved = $pengajuan->save();

            if (!$saved) {
                Log::error('Gagal menyimpan pengajuan: Save method returned false');
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan.')->withInput();
            }

            Log::info('Pengajuan berhasil disimpan dengan ID: ' . $pengajuan->id);
            return redirect()->route('mahasiswa.pengajuan.index')->with('success', 'Pengajuan berhasil dikirim.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengajuan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage())->withInput();
        }
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
