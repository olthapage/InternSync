<?php

namespace App\Http\Controllers\industri;

use App\Http\Controllers\Controller;
use App\Models\LogHarianDetailModel;
use App\Models\LogHarianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class LogHarianIndustriController extends Controller
{
    public function index()
    {
        $activeMenu = 'logharian_industri';
        return view('industri_page.logharian_industri.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $industriId = Auth::id();

        $query = LogHarianDetailModel::with(['logHarian.mahasiswaMagang.mahasiswa'])
            ->select('m_logharian_detail.*')
            ->whereHas('logHarian.mahasiswaMagang', function ($q) use ($industriId, $request) {
                $q->where('lowongan_id', $industriId);

                if ($request->filled('nama')) {
                    $q->whereHas('mahasiswa', function ($q2) use ($request) {
                        $q2->where('nama_lengkap', 'like', '%' . $request->nama . '%');
                    });
                }
            });

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($row) => $row->logHarian->tanggal ?? '-')
            ->addColumn('mahasiswa', fn($row) => $row->logHarian->mahasiswaMagang->mahasiswa->nama_lengkap ?? '-')
            ->addColumn('kegiatan', fn($row) => $row->isi ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi ?? '-')
            ->addColumn('status_dosen', function ($row) {
                $status = $row->status_approval_dosen ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning">Pending</span>';
            })
            ->addColumn('status_industri', function ($row) {
                $status = $row->status_approval_industri ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning">Pending</span>';
            })
            ->addColumn('aksi', function ($row) {

                $detailUrl = route('logharian_industri.show', ['id' => $row->logHarian->logHarian_id]);
                return '<button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">Detail</button>';
            })
            ->rawColumns(['status_dosen', 'status_industri', 'aksi'])
            ->make(true);
    }

    public function show(Request $request, $id)
    {
        $logharian = LogHarianModel::with(['mahasiswaMagang.mahasiswa', 'detail'])
            ->findOrFail($id);

        if ($request->ajax()) {
            return view('industri_page.logharian_industri.show', compact('logharian'));
        }
    }

    public function edit($id)
    {
        $log = LogHarianDetailModel::with('logHarian.mahasiswaMagang.mahasiswa')->findOrFail($id);

        if ($log->logHarian->mahasiswaMagang->mahasiswa->industri_id !== Auth::id()) {
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
                    'status_approval_industri' => $request->status,
                    'catatan_industri' => $request->catatan,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Approval industri berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}