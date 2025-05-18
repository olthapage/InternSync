<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganModel;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use App\Models\DetailLowonganModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class PengajuanController extends Controller
{
    public function index()
    {
        $activeMenu = 'pengajuan';
        return view('admin_page.pengajuan.index', compact('activeMenu'));
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
                $pk = $row->pengajuan_id;    
                $btn  = '<button onclick="modalAction(\'' . url("/pengajuan/{$pk}/show") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create(Request $request)
    {
        $mahasiswa = MahasiswaModel::all();
        $lowongan = DetailLowonganModel::all();

        if ($request->ajax()) {
            return view('admin_page.pengajuan.create', compact('mahasiswa', 'lowongan'));
        }

        $activeMenu = 'pengajuan';
        return view('admin_page.pengajuan.create', compact('mahasiswa', 'lowongan', 'activeMenu'));
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

            // Ambil lowongan yang dipilih
            $lowongan = DetailLowonganModel::find($request->lowongan_id);

            if (!$lowongan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lowongan tidak ditemukan'
                ]);
            }

            // Validasi apakah tanggal_pengajuan berada di dalam rentang tanggal lowongan
            $tanggalPengajuan = $request->tanggal_pengajuan;
            if ($tanggalPengajuan < $lowongan->tanggal_mulai || $tanggalPengajuan > $lowongan->tanggal_selesai) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tanggal pengajuan harus berada di antara tanggal mulai dan selesai lowongan'
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
            return view('admin_page.pengajuan.show', compact('pengajuan'));
        }
    }

    public function edit(Request $request, $id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $mahasiswa = MahasiswaModel::all();
        $lowongan = DetailLowonganModel::all();

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
