<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriIndustriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriIndustriController extends Controller
{
    public function index()
    {
        $kategori = KategoriIndustriModel::all();
        $activeMenu = 'kategori-industri';
        return view('admin_page.kategori_industri.index', compact('kategori', 'activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = KategoriIndustriModel::select('kategori_industri_id', 'kategori_industri_kode', 'kategori_nama');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function ($item) {
                    $btn = '<button onclick="modalAction(\'' . url('/kategori-industri/' . $item->kategori_industri_id . '/edit') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="deleteAction(\'' . url('/kategori-industri/' . $item->kategori_industri_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $activeMenu = 'kategori-industri';

        $lastKode = KategoriIndustriModel::latest('kategori_industri_kode')->first()?->kategori_industri_kode ?? 'KI000';

        $nextNumber = (int) substr($lastKode, 2) + 1;
        $kodeKategori = 'KI' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        if ($request->ajax()) {
            return view('admin_page.kategori_industri.create', compact('kodeKategori'));
        }

        $activeMenu = 'kategori-industri';
        return view('admin_page.kategori_industri.create', compact('kodeKategori', 'activeMenu'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'kategori_industri_kode' => 'required|unique:m_kategori_industri,kategori_industri_kode',
                    'kategori_nama' => 'required|string|max:255'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal',
                        'msgField' => $validator->errors(),
                    ]);
                }

                KategoriIndustriModel::create([
                    'kategori_industri_kode' => $request->kategori_industri_kode,
                    'kategori_nama' => $request->kategori_nama
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Kategori industri berhasil ditambahkan',
                ]);
            }

            return redirect()->route('kategori-industri.index');
        } catch (\Throwable $th) {
            Log::error('Error Store Kategori Industri: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $kategori = KategoriIndustriModel::find($id);
        $activeMenu = 'kategori-industri';

        if ($request->ajax()) {
            return view('admin_page.kategori_industri.show', compact('kategori'));
        }

        return view('admin_page.kategori_industri.show', compact('kategori', 'activeMenu'));
    }

    public function edit(Request $request, $id)
    {
        $kategori = KategoriIndustriModel::find($id);

        if ($request->ajax()) {
            return view('admin_page.kategori_industri.edit', compact('kategori'));
        }

        $activeMenu = 'kategori-industri';
        return view('admin_page.kategori_industri.edit', compact('kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|unique:m_kategori_industri,kategori_nama,' . $id . ',kategori_industri_id',
                'kategori_industri_kode' => 'required|unique:m_kategori_industri,kategori_industri_kode,' . $id . ',kategori_industri_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $kategori = KategoriIndustriModel::find($id);
            $kategori->update([
                'kategori_nama' => $request->kategori_nama,
                'kategori_industri_kode' => $request->kategori_industri_kode
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori industri berhasil diperbarui',
            ]);
        }

        return redirect()->route('kategori-industri.index');
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
                $kategori = KategoriIndustriModel::find($id);
                $kategori->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Kategori industri berhasil dihapus.',
                ]);
            }

        return redirect()->route('kategori-industri.destroy');
    }
}
