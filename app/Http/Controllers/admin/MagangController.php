<?php
namespace App\Http\Controllers\admin; // Penyesuaian namespace

use App\Http\Controllers\Controller;
use App\Models\IndustriModel;
use App\Models\MagangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MagangController extends Controller
{
    public function index()
    {
        $activeMenu = 'magang';
        $industri   = IndustriModel::all();
        return view('admin_page.magang.index', compact('activeMenu', 'industri'));
    }

    public function list(Request $request)
    {
        $magangData = MagangModel::query()
            ->with(['mahasiswa', 'lowongan', 'lowongan.industri']) // Eager load relasi, termasuk perusahaan dari lowongan
            ->select('mahasiswa_magang.*');                        // Pastikan memilih semua kolom dari tabel utama atau yang spesifik

        if ($request->has('industri_id') && $request->industri_id != '') {
            $magangData->whereHas('lowongan.industri', function ($q) use ($request) {
                $q->where('industri_id', $request->industri_id);
            });
        }

        return DataTables::of($magangData)
            ->addIndexColumn()
            ->addColumn('mahasiswa', function ($row) {
                return $row->mahasiswa->nama_lengkap ?? '-';
            })
            ->addColumn('lowongan', function ($row) {
                return $row->lowongan->judul_lowongan ?? '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'Diterima') {
                    return '<span class="badge badge-success">Diterima</span>';
                } elseif ($row->status == 'Ditolak') {
                    return '<span class="badge badge-danger">Ditolak</span>';
                } elseif ($row->status == 'Proses Seleksi') {
                    return '<span class="badge badge-warning">Proses Seleksi</span>';
                }
                return $row->status ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/magang/' . $row->mahasiswa_magang_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/magang/' . $row->mahasiswa_magang_id . '/edit') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/magang/' . $row->mahasiswa_magang_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                return $btn;
            })

            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }
}
