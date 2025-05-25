<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
            $mahasiswa = MahasiswaModel::with(['prodi', 'magang'])
                ->where('dosen_id', Auth::id()) // Hanya mahasiswa bimbingan dosen aktif
                ->select('mahasiswa_id', 'nama_lengkap', 'nim', 'prodi_id');

            return DataTables::of($mahasiswa)
                ->addIndexColumn()
                ->addColumn('prodi', function ($row) {
                    return $row->prodi->nama_prodi ?? '-';
                })
                ->addColumn('tempat_magang', function ($row) {
                    return $row->magang->lowongan->industri->industri_nama ?? '-';
                })
                ->addColumn('judul_lowongan', function ($row) {
                    return $row->magang->lowongan->judul_lowongan ?? '-';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->magang->status ?? 'Belum ada';
                    return match($status) {
                        'Diterima' => '<span class="badge badge-success">Diterima</span>',
                        'Ditolak' => '<span class="badge badge-danger">Ditolak</span>',
                        'Proses Seleksi' => '<span class="badge badge-warning">Proses Seleksi</span>',
                        default => $status
                    };
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/dosen/mahasiswa-bimbingan/' . $row->mahasiswa_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button>';
                    return $btn;
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function show(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'magang.lowongan.industri'])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);
        $activeMenu = 'mahasiswa-bimbingan';

        if ($request->ajax()) {
            return view('dosen_page.mahasiswa_bimbingan.show', compact('mahasiswa'));
        }

        return view('dosen_page.mahasiswa_bimbingan.show', compact('mahasiswa', 'activeMenu'));
    }
}
