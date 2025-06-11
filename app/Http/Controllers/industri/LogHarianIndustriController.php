<?php
namespace App\Http\Controllers\industri;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LogHarianModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\LogHarianDetailModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LogHarianIndustriController extends Controller
{
    public function index()
    {
        Log::info('[LogHarianIndustriController@index] Memasuki method index.');
        $activeMenu = 'logharian_industri';
        Log::info('[LogHarianIndustriController@index] Mengembalikan view untuk index log harian industri.');
        return view('industri_page.logharian_industri.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        Log::info('[LogHarianIndustriController@list] Memasuki method list.');

        if (! $request->ajax()) {
            Log::warning('[LogHarianIndustriController@list] Request bukan AJAX. Mengembalikan error 400.');
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $industriId = Auth::id(); // Ini adalah industri_id dari IndustriModel yang login
        Log::info("[LogHarianIndustriController@list] ID Industri Terautentikasi (dari Auth::id()): {$industriId}");

        if (! $industriId) { // Tambahan pengecekan jika $industriId null
            Log::error("[LogHarianIndustriController@list] Gagal mendapatkan ID Industri yang terautentikasi.");
            return DataTables::of(collect([]))->make(true);
        }

        $query = LogHarianDetailModel::with([
            'logHarian.mahasiswaMagang.mahasiswa',
            'logHarian.mahasiswaMagang.lowongan', // Pastikan lowongan di-load untuk filter
        ])
        // Filter berdasarkan industri_id yang ada di tabel lowongan (m_detail_lowongan)
        // melalui rantai relasi: LogHarianDetail -> LogHarian -> MahasiswaMagang -> Lowongan -> industri_id
            ->whereHas('logHarian.mahasiswaMagang.lowongan', function ($q_lowongan) use ($industriId) {
                Log::debug("[LogHarianIndustriController@list] Di dalam whereHas('logHarian.mahasiswaMagang.lowongan'). Memfilter dengan m_detail_lowongan.industri_id: {$industriId}");
                $q_lowongan->where('industri_id', $industriId); // INI FILTER YANG BENAR
            });

        // Tambahkan filter nama mahasiswa jika ada dari request
        if ($request->filled('nama')) {
            $namaFilter = $request->nama;
            Log::debug("[LogHarianIndustriController@list] Menerapkan filter nama: {$namaFilter}");
            // Filter nama mahasiswa diterapkan pada relasi mahasiswaMagang
            $query->whereHas('logHarian.mahasiswaMagang.mahasiswa', function ($q_mahasiswa) use ($namaFilter) {
                $q_mahasiswa->where('nama_lengkap', 'like', '%' . $namaFilter . '%');
            });
        } else {
            Log::debug("[LogHarianIndustriController@list] Filter nama tidak diisi.");
        }

        $countForLogging = (clone $query)->count(); // Clone query untuk count agar tidak mengganggu query utama DataTables
        Log::info("[LogHarianIndustriController@list] Jumlah record yang cocok dengan kriteria query (setelah perbaikan logika filter) sebelum diproses DataTables: {$countForLogging}");
        if ($countForLogging === 0) {
            Log::info("[LogHarianIndustriController@list] Tidak ada data log harian ditemukan setelah filter. Periksa apakah ada log harian untuk mahasiswa yang magang di industri ID: {$industriId}.");
        }

        return DataTables::of($query)
        // ... (sisa addColumn Anda sama seperti sebelumnya) ...
            ->addIndexColumn()
            ->addColumn('tanggal', function ($row) {
                // Sekarang $row->logHarian tidak akan null lagi
                $tanggal = $row->tanggal_kegiatan;

                if (is_null($tanggal)) {
                    Log::warning("[LogHarianIndustriController@list] addColumn 'tanggal': tanggal adalah null untuk detail ID: {$row->logHarianDetail_id}");
                    return '-';
                }
                // Langsung parse tanpa toDateTimeString()
                return Carbon::parse($tanggal)->isoFormat('D MMM YY');
            })
            ->addColumn('mahasiswa', function ($row) {
                $nama_lengkap = optional(optional(optional($row->logHarian)->mahasiswaMagang)->mahasiswa)->nama_lengkap;
                if (is_null($nama_lengkap)) {
                    Log::warning("[LogHarianIndustriController@list] addColumn 'mahasiswa': Data mahasiswa (nama_lengkap) adalah null untuk logHarianDetail_id: {$row->logHarianDetail_id}. Periksa relasi logHarian -> mahasiswaMagang -> mahasiswa.");
                }
                return $nama_lengkap ?? '-';
            })
            ->addColumn('kegiatan', fn($row) => $row->isi ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi ?? '-')
            ->addColumn('status_dosen', function ($row) {
                $status = $row->status_approval_dosen ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-gradient-success">Disetujui</span>';
                }

                if ($status == 'ditolak') {
                    return '<span class="badge bg-gradient-danger">Ditolak</span>';
                }

                return '<span class="badge bg-gradient-primary text-white">Pending</span>';
            })
            ->addColumn('status_industri', function ($row) {
                $status = $row->status_approval_industri ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-gradient-success">Disetujui</span>';
                }

                if ($status == 'ditolak') {
                    return '<span class="badge bg-gradient-danger">Ditolak</span>';
                }

                return '<span class="badge bg-gradient-primary text-white">Pending</span>';
            })
            ->addColumn('aksi', function ($row) {
                // $row adalah instance dari LogHarianDetailModel
                $buttons = '<div class="btn-group" role="group">';

                // --- Tombol Detail (untuk membuka modal) ---
                try {
                    $logHarianId = optional($row->logHarian)->logHarian_id;

                    if ($logHarianId) {
                        $detailUrl = route('logharian_industri.show', $logHarianId);
                        $buttons .= '<button class="btn btn-xs btn-outline-info" title="Lihat Detail Log" onclick="modalAction(\'' . $detailUrl . '\')"><i class="fas fa-eye"></i></button>';
                    } else {
                        // Nonaktifkan tombol jika relasi ke log harian header tidak ada
                        Log::warning("[LogHarianIndustriController@list] addColumn 'aksi': logHarianId tidak ditemukan untuk detail ID: {$row->logHarianDetail_id}");
                        $buttons .= '<button class="btn btn-xs btn-outline-secondary disabled" title="Data tidak lengkap"><i class="fas fa-eye"></i></button>';
                    }
                } catch (\Exception $e) {
                    // Tangani error jika route 'logharian_industri.show' tidak ditemukan
                    Log::error("Error membuat URL 'detail' untuk log detail ID {$row->logHarianDetail_id}: " . $e->getMessage());
                    $buttons .= '<button class="btn btn-xs btn-outline-danger disabled" title="Route error"><i class="fas fa-eye"></i></button>';
                }

                // --- Tombol Kelola (untuk redirect ke halaman lain) ---
                try {
                    $mahasiswaMagangId = optional(optional($row->logHarian)->mahasiswaMagang)->mahasiswa_magang_id;

                    if ($mahasiswaMagangId) {
                        $actionUrl = route('industri.magang.action', $mahasiswaMagangId);
                        $buttons .= '<a href="' . $actionUrl . '" class="btn btn-xs btn-outline-primary" title="Kelola Magang Mahasiswa"><i class="fas fa-tasks"></i></a>';
                    } else {
                        // Nonaktifkan tombol jika relasi ke magang mahasiswa tidak ada
                        Log::warning("[LogHarianIndustriController@list] addColumn 'aksi': mahasiswaMagangId tidak ditemukan untuk detail ID: {$row->logHarianDetail_id}");
                        $buttons .= '<a href="#" class="btn btn-xs btn-outline-secondary disabled" title="Data tidak lengkap"><i class="fas fa-tasks"></i></a>';
                    }
                } catch (\Exception $e) {
                    // Tangani error jika route 'industri.magang.action' tidak ditemukan
                    Log::error("Error membuat URL 'kelola' untuk log detail ID {$row->logHarianDetail_id}: " . $e->getMessage());
                    $buttons .= '<a href="#" class="btn btn-xs btn-outline-danger disabled" title="Route error"><i class="fas fa-tasks"></i></a>';
                }

                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status_dosen', 'status_industri', 'aksi'])
            ->make(true);
    }

    public function show(Request $request, $id) // $id di sini adalah logHarian_id
    {
        Log::info("[LogHarianIndustriController@show] Memasuki method show untuk logHarian_id: {$id}. AJAX: " . ($request->ajax() ? 'Ya' : 'Tidak'));
        $industriId = Auth::id(); // ID industri yang login
        Log::info("[LogHarianIndustriController@show] ID Industri Terautentikasi: {$industriId}");

        $logharian = LogHarianModel::with([
            'mahasiswaMagang.mahasiswa',
            'mahasiswaMagang.lowongan', // Eager load lowongan untuk akses industri_id
            'detail' => function ($query) {
                $query->orderBy('tanggal_kegiatan', 'desc'); // Urutkan detail log
            },
        ])
            ->whereHas('mahasiswaMagang.lowongan', function ($q_lowongan) use ($industriId) {
                // Filter untuk memastikan log harian ini milik industri yang login
                $q_lowongan->where('industri_id', $industriId);
                Log::debug("[LogHarianIndustriController@show] Filter otorisasi: m_detail_lowongan.industri_id = {$industriId}");
            })
            ->find($id); // Gunakan find() setelah whereHas untuk efisiensi jika ID adalah PK

        if (! $logharian) {
            Log::warning("[LogHarianIndustriController@show] LogHarian tidak ditemukan atau tidak diotorisasi untuk logHarian_id: {$id} dan Industri ID: {$industriId}");
            if ($request->ajax()) {
                // Anda mungkin perlu membuat partial view untuk pesan error di modal
                return response()->view('partials.modal_error_content', ['message' => 'Detail log harian tidak ditemukan atau Anda tidak berhak mengaksesnya.'], 404);
            }
            abort(404, 'Detail log harian tidak ditemukan atau tidak berhak diakses.');
        }

        Log::info("[LogHarianIndustriController@show] LogHarian ditemukan untuk id: {$id}. Mahasiswa: " . (optional(optional($logharian->mahasiswaMagang)->mahasiswa)->nama_lengkap ?? 'N/A'));

        if ($request->ajax()) {
            Log::debug("[LogHarianIndustriController@show] Mengembalikan view 'industri_page.logharian_industri.show' via AJAX.");
            return view('industri_page.logharian_industri.show', compact('logharian'));
        }

        Log::warning("[LogHarianIndustriController@show] Request non-AJAX ke method show untuk logHarian_id: {$id}. Ini tidak biasa untuk konten modal.");
                                                                                                       // Fallback jika bukan AJAX, mungkin tampilkan halaman penuh atau redirect
        return view('industri_page.logharian_industri.show_full_page_fallback', compact('logharian')); // Buat view fallback jika perlu
    }

    public function edit($id) // $id di sini adalah logHarianDetail_id
    {
        Log::info("[LogHarianIndustriController@edit] Memasuki method edit untuk logHarianDetail_id: {$id}");
        $authIndustriId = Auth::id();
        Log::info("[LogHarianIndustriController@edit] ID Industri Terautentikasi: {$authIndustriId}");

        $log = LogHarianDetailModel::with('logHarian.mahasiswaMagang.lowongan.industri')
            ->findOrFail($id);

                                                                                                                                 // Otorisasi yang Diperbaiki
        $logIndustriId = optional(optional(optional(optional($log->logHarian)->mahasiswaMagang)->lowongan)->industri)->getKey(); // Asumsi relasi 'industri' ada di 'lowongan' dan mengembalikan model Industri

        Log::debug("[LogHarianIndustriController@edit] logHarianDetail_id: {$id}, ID Industri Log: {$logIndustriId}");

        if ($logIndustriId != $authIndustriId) { // Perbandingan ketat lebih baik jika tipe data pasti sama
            Log::warning("[LogHarianIndustriController@edit] Upaya akses tidak sah. ID Industri Auth: {$authIndustriId}, ID Industri Log: {$logIndustriId}");
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        Log::info("[LogHarianIndustriController@edit] Otorisasi berhasil untuk logHarianDetail_id: {$id}");
        return response()->json($log);
    }

    public function approval(Request $request)
    {
        Log::info("[LogHarianIndustriController@approval] Memasuki method approval.");
        Log::debug("[LogHarianIndustriController@approval] Data request: ", $request->all());

        $request->validate([
            'status'      => 'required|in:disetujui,ditolak,pending',
            'catatan'     => 'nullable|string',
            'logHarianId' => 'required|exists:m_logharian_detail,logHarian_id',
        ]);

        Log::info("[LogHarianIndustriController@approval] Validasi berhasil untuk logHarianDetailId: {$request->logHarianId}, Status Baru: {$request->status}");

        $authIndustriId = Auth::id();
        Log::info("[LogHarianIndustriController@approval] ID Industri Terautentikasi: {$authIndustriId}");

        $logDetail = LogHarianDetailModel::with('logHarian.mahasiswaMagang.lowongan.industri')
            ->find($request->logHarianId);

        if (! $logDetail) {
            Log::error("[LogHarianIndustriController@approval] LogHarianDetail tidak ditemukan untuk ID: {$request->logHarianId}");
            return response()->json(['success' => false, 'message' => 'Detail log harian tidak ditemukan.'], 404);
        }

        // Otorisasi
        $logIndustriId = optional(optional(optional(optional($logDetail->logHarian)->mahasiswaMagang)->lowongan)->industri)->getKey();
        Log::debug("[LogHarianIndustriController@approval] Cek otorisasi. ID Industri Auth: {$authIndustriId}, ID Industri Log: {$logIndustriId}");

        if ($logIndustriId != $authIndustriId) {
            Log::warning("[LogHarianIndustriController@approval] Tidak Sah. ID Industri Auth: {$authIndustriId} != ID Industri Log: {$logIndustriId}");
            return response()->json(['success' => false, 'message' => 'Aksi tidak diizinkan.'], 403);
        }
        Log::info("[LogHarianIndustriController@approval] Otorisasi berhasil.");

        try {
            $logDetail->status_approval_industri = $request->status;
            $logDetail->catatan_industri         = $request->catatan;
            $logDetail->save(); // updated_at akan dihandle otomatis oleh Eloquent

            Log::info("[LogHarianIndustriController@approval] Approval industri berhasil disimpan untuk logHarianDetailId: {$request->logHarianId}");
            return response()->json([
                'success' => true,
                'message' => 'Approval industri berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error("[LogHarianIndustriController@approval] Gagal menyimpan data: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
