<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Log;
use App\Models\KotaModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\KategoriIndustriModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IndustriController extends Controller
{
    public function index()
    {
        $industri = IndustriModel::with('kota', 'kategori_industri')->get();
        $activeMenu = 'industri';
        return view('admin_page.industri.index', compact('industri', 'activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = IndustriModel::with(['kota', 'kategori_industri'])->select('industri_id', 'industri_nama', 'kota_id', 'kategori_industri_id');

            if ($request->filled('kota_id')) {
                $query->where('kota_id', $request->kota_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('kota', function ($item) {
                    return $item->kota->kota_nama ?? '-';
                })
                ->addColumn('kategori', function ($item) {
                    return $item->kategori_industri->kategori_nama ?? '-';
                })
                ->addColumn('aksi', function ($item) {
                    $btn  = '<button onclick="modalAction(\'' . url('/industri/' . $item->industri_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/industri/' . $item->industri_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="deleteAction(\'' . url('/industri/' . $item->industri_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $kota = KotaModel::all();
        $kategori = KategoriIndustriModel::all();
        $activeMenu = 'industri';

        if ($request->ajax()) {
            return view('admin_page.industri.create', compact('kota', 'kategori'));
        }

        return view('admin_page.industri.create', compact('kota', 'kategori', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'industri_nama' => 'required|string|max:255',
                'kota_id' => 'required|exists:m_kota,kota_id',
                'kategori_industri_id' => 'required|exists:m_kategori_industri,kategori_industri_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            IndustriModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Industri berhasil ditambahkan'
            ]);
        }

        return redirect()->route('industri.index');
    }

    public function show(Request $request, $id)
    {
        $industri = IndustriModel::with(['kota', 'kategori_industri'])->find($id);
        $activeMenu = 'industri';

        if ($request->ajax()) {
            return view('admin_page.industri.show', compact('industri', 'activeMenu'));
        }

        return view('admin_page.industri.show', compact('industri', 'activeMenu'));
    }

    public function edit(Request $request, $id)
    {
        $industri = IndustriModel::find($id);
        $kota = KotaModel::all();
        $kategori = KategoriIndustriModel::all();

        if ($request->ajax()) {
            return view('admin_page.industri.edit', compact('industri', 'kota', 'kategori'));
        }

        $activeMenu = 'industri';
        return view('admin_page.industri.edit', compact('industri', 'kota', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
                'industri_nama' => 'required|string|max:255',
                'kota_id' => 'required|exists:m_kota,kota_id',
                'kategori_industri_id' => 'required|exists:m_kategori_industri,kategori_industri_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $industri = IndustriModel::find($id);
            $industri->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Industri berhasil diperbarui'
            ]);
        }

        return redirect()->route('industri.index');
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $industri = IndustriModel::find($id);
            $industri->delete();

            return response()->json([
                'status' => true,
                'message' => 'Industri berhasil dihapus.'
            ]);
        }

        return redirect()->route('industri.index');
    }
}
