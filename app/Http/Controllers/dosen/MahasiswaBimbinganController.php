<?php
namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

// Tambahkan ini

class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        $activeMenu = 'mahasiswa-bimbingan';
        return view('dosen_page.mahasiswa_bimbingan.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $mahasiswa = MahasiswaModel::with(['prodi', 'magang.lowongan.industri'])
                ->where('dosen_id', Auth::id())
            // LANGKAH 1: Pastikan kolom 'foto' ada di sini
                ->select('m_mahasiswa.mahasiswa_id', 'm_mahasiswa.nama_lengkap', 'm_mahasiswa.nim', 'm_mahasiswa.prodi_id', 'm_mahasiswa.foto');

            return DataTables::of($mahasiswa)
                ->addIndexColumn()

            // LANGKAH 2: Gunakan editColumn untuk mengubah tampilan kolom 'nama_lengkap'
                ->editColumn('nama_lengkap', function ($row) {
                    // Asumsi Anda punya accessor 'foto_url' di MahasiswaModel, jika tidak, buat logika URL di sini
                    $fotoUrl = optional($row)->foto_url ?? asset('assets/default-profile.png');

                    // Gunakan htmlspecialchars untuk keamanan
                    $nama = htmlspecialchars($row->nama_lengkap, ENT_QUOTES, 'UTF-8');
                    $nim  = htmlspecialchars($row->nim, ENT_QUOTES, 'UTF-8');

                    // Bangun HTML sesuai referensi Anda
                    return '
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img src="' . $fotoUrl . '" class="avatar avatar-sm me-3" alt="' . $nama . '">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">' . $nama . '</h6>
                            <p class="text-xs text-secondary mb-0">NIM: ' . $nim . '</p>
                        </div>
                    </div>
                ';
                })

                ->addColumn('prodi', function ($row) {
                    return $row->prodi->nama_prodi ?? '-';
                })
                ->addColumn('tempat_magang', function ($row) {
                    return optional($row->magang->lowongan->industri)->industri_nama ?? '-';
                })
                ->addColumn('judul_lowongan', function ($row) {
                    return optional($row->magang->lowongan)->judul_lowongan ?? '-';
                })
                ->addColumn('status_magang', function ($row) {
                    $status                     = optional($row->magang)->status ?? 'belum';
                    [$badgeClass, $displayText] = match ($status) {
                        'sedang' => ['success', 'Sedang Magang'],
                        'selesai' => ['info', 'Selesai'],
                        'belum'   => ['secondary', 'Belum Magang'],
                        default   => ['dark', ucfirst($status)],
                    };
                    return '<span class="badge badge-sm bg-gradient-' . $badgeClass . '">' . $displayText . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    // Buat URL untuk modal
                    $url = route('mahasiswa-bimbingan.show', $row->mahasiswa_id);

                    // Gunakan tag <button> dengan atribut onclick untuk memanggil JavaScript.
                    // Ini tidak akan menyebabkan navigasi halaman.
                    return '<button onclick="modalAction(\'' . $url . '\')" class="btn btn-info btn-sm" title="Lihat Detail">
                            <i class="fas fa-eye"></i> Detail
                        </button>';
                })

            // LANGKAH 3: Tambahkan 'nama_lengkap' ke rawColumns
                ->rawColumns(['nama_lengkap', 'status_magang', 'aksi'])
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request.'], 400);
    }

    public function show(Request $request, $id)
    {
        Log::info("MahasiswaBimbinganController@show: Attempting to show details for mahasiswa_id: {$id}");
        Log::info("MahasiswaBimbinganController@show: Dosen ID from Auth: " . Auth::id());

        try {
            $mahasiswa = MahasiswaModel::with([
                'prodi',
                'magang.lowongan.industri',
                'dosenPembimbing',
                'dpa',
            ])
                ->where('dosen_id', Auth::id()) // PERBAIKAN: Menggunakan Auth::id()
                ->findOrFail($id);

            Log::info("MahasiswaBimbinganController@show: Mahasiswa data found for ID {$id}: " . $mahasiswa->nama_lengkap);

            $activeMenu = 'mahasiswa-bimbingan';
            $action     = $request->query('action');
            Log::info("MahasiswaBimbinganController@show: Action parameter: {$action}");

            if ($request->ajax()) {
                Log::info("MahasiswaBimbinganController@show: Request is AJAX. Returning view 'dosen_page.mahasiswa_bimbingan.show' for modal.");
                return view('dosen_page.mahasiswa_bimbingan.show', compact('mahasiswa', 'action'));
            }

            Log::info("MahasiswaBimbinganController@show: Request is NOT AJAX. Returning full page view 'dosen_page.mahasiswa_bimbingan.show'.");
            return view('dosen_page.mahasiswa_bimbingan.show', compact('mahasiswa', 'activeMenu', 'action'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("MahasiswaBimbinganController@show: Mahasiswa with ID {$id} not found for dosen_id " . Auth::id() . ". Error: " . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Data mahasiswa tidak ditemukan atau Anda tidak memiliki akses.'], 404);
            }
            // Untuk non-AJAX, Anda bisa redirect atau tampilkan halaman error khusus
            abort(404, 'Data mahasiswa tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error("MahasiswaBimbinganController@show: An unexpected error occurred for ID {$id}. Error: " . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
            }
            abort(500, 'Terjadi kesalahan pada server.');
        }
    }

    public function updateFeedback(Request $request, $mahasiswa_magang_id)
    {
        Log::info("MahasiswaBimbinganController@updateFeedback: Attempting to update feedback for mahasiswa_magang_id: {$mahasiswa_magang_id}");
        $validator = Validator::make($request->all(), [
            'feedback_dosen' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            Log::warning("MahasiswaBimbinganController@updateFeedback: Validation failed for mahasiswa_magang_id: {$mahasiswa_magang_id}. Errors: ", $validator->errors()->toArray());
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $magang = MagangModel::where('mahasiswa_magang_id', $mahasiswa_magang_id)
                ->whereHas('mahasiswa', function ($query) {
                    $query->where('dosen_id', Auth::id());
                })
                ->firstOrFail();

            Log::info("MahasiswaBimbinganController@updateFeedback: Magang data found for mahasiswa_magang_id: {$mahasiswa_magang_id}. Current status: " . $magang->status);

            if ($magang->status !== 'selesai') {
                Log::warning("MahasiswaBimbinganController@updateFeedback: Feedback attempt on non-selesai magang (status: {$magang->status}) for mahasiswa_magang_id: {$mahasiswa_magang_id}");
                return response()->json(['success' => false, 'message' => 'Feedback hanya bisa diberikan jika status magang sudah selesai.'], 403);
            }

            $magang->feedback_dosen = $request->feedback_dosen;
            $magang->save();
            Log::info("MahasiswaBimbinganController@updateFeedback: Feedback successfully saved for mahasiswa_magang_id: {$mahasiswa_magang_id}");

            return response()->json(['success' => true, 'message' => 'Feedback berhasil disimpan.']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("MahasiswaBimbinganController@updateFeedback: Magang data not found for mahasiswa_magang_id {$mahasiswa_magang_id} or access denied for dosen_id " . Auth::id() . ". Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Data magang tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        } catch (\Exception $e) {
            Log::error("MahasiswaBimbinganController@updateFeedback: Unexpected error for mahasiswa_magang_id {$mahasiswa_magang_id}. Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan feedback: ' . $e->getMessage()], 500);
        }
    }
}
