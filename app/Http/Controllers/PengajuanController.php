<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use App\Models\MahasiswaModel;
use App\Models\LowonganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PengajuanController extends Controller
{
    public function index()
    {
        $activeMenu = 'pengajuan';
        return view('pengajuan.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        $pengajuan = PengajuanModel::with(['mahasiswa', 'lowongan']);

        // Filter berdasarkan rentang tanggal pengajuan
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pengajuan->whereBetween('tanggal_pengajuan', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('mahasiswa', fn($row) => $row->mahasiswa->nama_lengkap ?? '-')
            ->addColumn('lowongan', fn($row) => $row->lowongan->judul_lowongan ?? '-')
            ->addColumn('tanggal_pengajuan_mulai', fn($row) => $row->tanggal_mulai ?? '-')
            ->addColumn('tanggal_pengajuan_selesai', fn($row) => $row->tanggal_selesai ?? '-')
            ->addColumn('status_pengajuan', fn($row) => ucfirst($row->status) ?? '-')
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/pengajuan/' . $row->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengajuan/' . $row->id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengajuan/' . $row->id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create(Request $request)
    {
        $mahasiswa = MahasiswaModel::all();
        $lowongan = LowonganModel::all();

        if ($request->ajax()) {
            return view('pengajuan.create', compact('mahasiswa', 'lowongan'));
        }

        $activeMenu = 'pengajuan';
        return view('pengajuan.create', compact('mahasiswa', 'lowongan', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required',
                'lowongan_id' => 'required',
                'tanggal_pengajuan' => 'required|date',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PengajuanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Pengajuan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function show(Request $request, $id)
    {
        $pengajuan = PengajuanModel::with(['mahasiswa', 'lowongan'])->find($id);

        if ($request->ajax()) {
            return view('pengajuan.show', compact('pengajuan'));
        }
    }

    public function edit(Request $request, $id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $mahasiswa = MahasiswaModel::all();
        $lowongan = LowonganModel::all();

        if ($request->ajax()) {
            return view('pengajuan.edit', compact('pengajuan', 'mahasiswa', 'lowongan'));
        }

        $activeMenu = 'pengajuan';
        return view('pengajuan.edit', compact('pengajuan', 'mahasiswa', 'lowongan', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required',
                'lowongan_id' => 'required',
                'tanggal_pengajuan' => 'required|date',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $pengajuan = PengajuanModel::find($id);
            if ($pengajuan) {
                $pengajuan->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Pengajuan berhasil diperbarui'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    public function deleteModal(Request $request, $id)
    {
        $pengajuan = PengajuanModel::with(['mahasiswa', 'lowongan'])->find($id);
        return view('pengajuan.delete', compact('pengajuan'));
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
                'status' => true,
                'message' => 'Pengajuan berhasil dihapus'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
