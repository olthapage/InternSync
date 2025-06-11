<?php
namespace App\Http\Controllers\mahasiswa;

use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use Illuminate\Support\Carbon;
use App\Models\KategoriSkillModel;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengajuanController extends Controller
{
    public function index()
    {
        $activeMenu = 'pengajuan';
        $mahasiswa = auth()->user();
        $mahasiswaId = $mahasiswa->mahasiswa_id;

        // Ambil riwayat pengajuan untuk ditampilkan (tidak ada perubahan di sini)
        $pengajuan = PengajuanModel::with(['lowongan.industri'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // --- LOGIKA BARU UNTUK MENGHUBUNGKAN STATUS MAGANG ---
        if ($pengajuan->isNotEmpty()) {
            // 1. Kumpulkan semua lowongan_id dari pengajuan yang ada
            $lowonganIds = $pengajuan->pluck('lowongan_id')->unique();

            // 2. Ambil semua data magang yang relevan dalam satu query
            $dataMagang = MagangModel::where('mahasiswa_id', $mahasiswaId)
                                     ->whereIn('lowongan_id', $lowonganIds)
                                     ->get()
                                     // 3. Kelompokkan berdasarkan lowongan_id untuk pencarian cepat
                                     ->keyBy('lowongan_id');

            // 4. Lampirkan data magang ke setiap item pengajuan
            $pengajuan->each(function ($item) use ($dataMagang) {
                // Buat properti baru 'magang' di setiap item pengajuan
                $item->magang = $dataMagang->get($item->lowongan_id);
            });
        }

        // Logika untuk kontrol pengajuan (tidak ada perubahan di sini)
        $bisaAjukan = true;
        $alasanTidakBisaAjukan = '';
        $pengajuanDiproses = $pengajuan->firstWhere('status', 'belum');
        if ($pengajuanDiproses) {
            $bisaAjukan = false;
            $alasanTidakBisaAjukan = 'Anda masih memiliki pengajuan yang sedang diproses. Mohon tunggu hasilnya sebelum membuat pengajuan baru.';
        } else {
            $magangAktif = MagangModel::where('mahasiswa_id', $mahasiswaId)
                                      ->whereIn('status', ['belum', 'sedang'])
                                      ->exists();
            if ($magangAktif) {
                $bisaAjukan = false;
                $alasanTidakBisaAjukan = 'Anda sedang dalam periode magang aktif. Anda baru dapat mengajukan magang lagi setelah periode saat ini selesai.';
            }
        }

        $profilLengkap = $mahasiswa && $mahasiswa->status_verifikasi == 'valid';

        return view('mahasiswa_page.pengajuan.index', compact(
            'activeMenu',
            'pengajuan',
            'profilLengkap',
            'bisaAjukan',
            'alasanTidakBisaAjukan'
        ));
    }

    public function create($id = null)
    {
        $lowonganList      = DetailLowonganModel::with(['industri', 'kategoriSkill'])->get();
        $industriList      = IndustriModel::all();
        $kategoriSkillList = KategoriSkillModel::all();
        $activeMenu = 'pengajuan';

        $selectedLowongan = null;
        if ($id) {
            $selectedLowongan = DetailLowonganModel::with(['industri', 'kategoriSkill'])->find($id);
        }

        return view('mahasiswa_page.pengajuan.create', compact('lowonganList', 'industriList', 'kategoriSkillList', 'selectedLowongan', 'activeMenu'));
    }

    public function store(Request $request)
    {
        // Log awal proses
        Log::info('Memulai proses pengajuan dengan data: ', $request->all());

        // Karena MahasiswaModel adalah Authenticatable, langsung gunakan auth()->user()
        $mahasiswa = auth()->user();

        if (! $mahasiswa) {
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
            $pengajuan                  = new PengajuanModel();
            $pengajuan->mahasiswa_id    = $mahasiswa->mahasiswa_id;
            $pengajuan->lowongan_id     = $request->lowongan_id;
            $pengajuan->tanggal_mulai   = Carbon::parse($request->tanggal_mulai);
            $pengajuan->tanggal_selesai = Carbon::parse($request->tanggal_selesai);
            $pengajuan->status          = 'belum'; // default sesuai enum

            // Log data sebelum disimpan
            Log::info('Data yang akan disimpan: ', $pengajuan->toArray());

            $saved = $pengajuan->save();

            if (! $saved) {
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
        Log::info('MahasiswaPengajuanController@show: Method entry. Requested Pengajuan ID: ' . $id . '. AJAX: ' . ($request->ajax() ? 'Yes' : 'No'));

        $pengajuan = PengajuanModel::with([
            'mahasiswa.prodi',
            'lowongan.industri',
            'lowongan.kategoriSkill',
        ])->find($id);

        if (!$pengajuan) {
            Log::warning('MahasiswaPengajuanController@show: Pengajuan NOT found for ID: ' . $id . '. Triggering 404.');
            if ($request->ajax()) {
                return response()->view('mahasiswa_page.pengajuan.partials.modal_error', ['message' => 'Detail pengajuan tidak ditemukan (ID: '.$id.').'], 404);
            }
            return abort(404, 'Detail pengajuan tidak ditemukan.');
        }
        Log::info('MahasiswaPengajuanController@show: Pengajuan found. Pengajuan ID: ' . $pengajuan->pengajuan_id . ', Belongs to Mahasiswa ID: ' . $pengajuan->mahasiswa_id);

         $magang = null; // Default null
        // Hanya cari data magang jika status pengajuan sudah 'diterima'
        if (strtolower($pengajuan->status) === 'diterima') {
            $magang = MagangModel::where('mahasiswa_id', $pengajuan->mahasiswa_id)
                                 ->where('lowongan_id', $pengajuan->lowongan_id)
                                 ->first();
        }

        $user = Auth::user(); // Ini seharusnya instance MahasiswaModel

        if (!$user || !($user instanceof \App\Models\MahasiswaModel)) {
            Log::warning('MahasiswaPengajuanController@show: User not authenticated as MahasiswaModel for authorization. User: ' . ($user ? get_class($user) : 'null') . '. Pengajuan ID: ' . $id);
            if ($request->ajax()) {
                return response()->view('mahasiswa_page.pengajuan.partials.modal_error', ['message' => 'Sesi tidak valid untuk otorisasi.'], 403);
            }
            return abort(403, 'Sesi tidak valid.');
        }

        // $user adalah instance MahasiswaModel yang sudah terautentikasi
        $mahasiswaAutentik = $user;
        Log::info('MahasiswaPengajuanController@show: Authenticated Mahasiswa for authorization. ID: ' . $mahasiswaAutentik->mahasiswa_id . '. Checking against Pengajuan\'s Mahasiswa ID: ' . $pengajuan->mahasiswa_id);


        if ($pengajuan->mahasiswa_id !== $mahasiswaAutentik->mahasiswa_id) {
            Log::warning('MahasiswaPengajuanController@show: Authorization FAILED. Pengajuan ID: ' . $id . ' (belongs to Mhs ID: ' . $pengajuan->mahasiswa_id . ') does not match authenticated Mahasiswa ID: ' . $mahasiswaAutentik->mahasiswa_id . '. Triggering 403.');
            if ($request->ajax()) {
                return response()->view('mahasiswa_page.pengajuan.partials.modal_error', ['message' => 'Akses ditolak. Anda hanya dapat melihat pengajuan Anda sendiri.'], 403);
            }
            return abort(403, 'Akses Ditolak.');
        }
        Log::info('MahasiswaPengajuanController@show: Authorization successful for Pengajuan ID: ' . $id);

        if ($request->ajax()) {
            Log::info('MahasiswaPengajuanController@show: AJAX request. Returning view mahasiswa_page.pengajuan.show for Pengajuan ID: ' . $id);
            return view('mahasiswa_page.pengajuan.show', compact('pengajuan', 'magang'));
        }

        Log::info('MahasiswaPengajuanController@show: Non-AJAX request. Redirecting for Pengajuan ID: ' . $id);
        return redirect()->route('mahasiswa.pengajuan.index')->with('warning', 'Detail pengajuan hanya bisa dilihat melalui modal.');
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
