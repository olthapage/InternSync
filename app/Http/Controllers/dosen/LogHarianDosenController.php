<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\LogHarianDetailModel;
use App\Models\LogHarianModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class LogHarianDosenController extends Controller
{
    public function index()
    {
        $activeMenu = 'logharian_dosen';
        return view('dosen_page.logharian_dosen.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $dosenId = Auth::id();

        $query = LogHarianDetailModel::with(['logHarian.mahasiswaMagang.mahasiswa'])
            ->whereHas('logHarian.mahasiswaMagang.mahasiswa', function ($q) use ($dosenId, $request) {
                $q->where('dosen_id', $dosenId);
                if ($request->filled('nama')) {
                    $q->where('nama_lengkap', 'like', '%' . $request->nama . '%');
                }
            });

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($row) => $row->logHarian?->tanggal ?? '-')
            ->addColumn('mahasiswa', fn($row) => $row->logHarian?->mahasiswaMagang?->mahasiswa?->nama_lengkap ?? '-')
            ->addColumn('kegiatan', fn($row) => $row->isi ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi ?? '-')
            ->addColumn('status_industri', function ($row) {
                $status = $row->status_approval_industri ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning text-dark">Pending</span>';
            })
            ->addColumn('status_dosen', function ($row) {
                $status = $row->status_approval_dosen ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning text-dark">Pending</span>';
            })
            ->addColumn('aksi', function ($row) {
                // PERBAIKAN: Kirim ID dari logHarian (induk), bukan logHarianDetail (anak)
                if ($row->logHarian) {
                    $detailUrl = route('logharian_dosen.show', ['id' => $row->logHarian->logHarian_id]);
                    return '<button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">Detail</button>';
                }
                return '<button class="btn btn-sm btn-secondary" disabled>Detail</button>';
            })
            ->rawColumns(['status_industri', 'status_dosen', 'aksi'])
            ->make(true);
    }
    public function show(Request $request, $id)
    {
        $logharian = LogHarianModel::with(['mahasiswaMagang.mahasiswa', 'detail'])
            ->findOrFail($id);

        if ($request->ajax()) {
            return view('dosen_page.logharian_dosen.show', compact('logharian'));
        }
    }

    public function edit($id)
    {
        $log = LogHarianDetailModel::with('logHarian.mahasiswaMagang.mahasiswa')->findOrFail($id);

        if ($log->logHarian->mahasiswaMagang->mahasiswa->dosen_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($log);
    }
    public function approval(Request $request)
{
    $request->validate([
        'status' => 'required|in:disetujui,ditolak,pending',
        'catatan' => 'nullable|string',
        'logHarianId' => 'required|exists:m_logharian_detail,logHarian_id',
    ]);

    try {
        DB::table('m_logharian_detail')
            ->where('logHarian_id', $request->logHarianId)
            ->update([
                'status_approval_dosen' => $request->status,
                'catatan_dosen' => $request->catatan,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Approval dosen berhasil disimpan.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan data: ' . $e->getMessage()
        ],500);
    }
}
}
