<?php

namespace App\Http\Controllers\dosen;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\MagangModel; // Tambahkan ini

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
            // Pastikan relasi magang.lowongan.industri sudah di-load jika akan diakses
            // Untuk efisiensi, load hanya yang dibutuhkan untuk list
            $mahasiswa = MahasiswaModel::with(['prodi', 'magang.lowongan.industri'])
                ->where('dosen_id', Auth::id()) // Hanya mahasiswa bimbingan dosen aktif
                ->select('m_mahasiswa.mahasiswa_id', 'm_mahasiswa.nama_lengkap', 'm_mahasiswa.nim', 'm_mahasiswa.prodi_id'); // Spesifikasikan kolom dari m_mahasiswa

            return DataTables::of($mahasiswa)
                ->addIndexColumn()
                ->addColumn('prodi', function ($row) {
                    return $row->prodi->nama_prodi ?? '-';
                })
                ->addColumn('tempat_magang', function ($row) {
                    // Cek apakah magang dan lowongan ada sebelum mengakses industri_nama
                    return $row->magang && $row->magang->lowongan && $row->magang->lowongan->industri ? $row->magang->lowongan->industri->industri_nama : '-';
                })
                ->addColumn('judul_lowongan', function ($row) {
                    // Cek apakah magang dan lowongan ada
                    return $row->magang && $row->magang->lowongan ? $row->magang->lowongan->judul_lowongan : '-';
                })
                ->addColumn('status_magang', function ($row) { // Ganti nama kolom agar lebih jelas
                    // Cek apakah magang ada
                    $status = $row->magang ? $row->magang->status : 'Belum ada'; // Status magang dari tabel mahasiswa_magang
                    switch ($status) {
                        case 'selesai':
                            return '<span class="badge-primary">Selesai</span>';
                        case 'sedang':
                            return '<span class="badge-info">Sedang Magang</span>';
                        case 'belum':
                            return '<span class="badge-secondary">Belum/Proses</span>';
                        default:
                            // Fallback jika tidak ada status spesifik, tampilkan status magang ('belum', 'sedang', 'selesai')
                             return match(strtolower((string)$status)) {
                                'selesai' => '<span class="badge badge-success">Selesai</span>',
                                'sedang' => '<span class="badge badge-info">Sedang Magang</span>',
                                'belum' => '<span class="badge badge-dark">Belum Magang</span>', // Status default jika 'belum'
                                default => '<span class="badge badge-info">' . htmlspecialchars($status) . '</span>'
                            };
                    }
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . route('mahasiswa-bimbingan.show', $row->mahasiswa_id) . '\')" class="btn btn-info btn-sm mr-1 mb-1"><i class="fas fa-pen"><i/></button>';
                    return $btn;
                })
                ->rawColumns(['status_magang', 'aksi']) // Pastikan status_magang di rawColumns
                ->make(true);
        }

        // Ini tidak akan pernah tercapai jika request adalah AJAX dan berhasil diproses oleh DataTables
        return response()->json(['message' => 'Invalid request. AJAX request expected.'], 400);
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
                'dpa'
            ])
            ->where('dosen_id', Auth::id()) // PERBAIKAN: Menggunakan Auth::id()
            ->findOrFail($id);

            Log::info("MahasiswaBimbinganController@show: Mahasiswa data found for ID {$id}: " . $mahasiswa->nama_lengkap);

            $activeMenu = 'mahasiswa-bimbingan';
            $action = $request->query('action');
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
