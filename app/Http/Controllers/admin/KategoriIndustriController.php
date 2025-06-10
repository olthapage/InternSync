<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriIndustriModel;
use Illuminate\Http\Request;
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
        $kategori = KategoriIndustriModel::select('kategori_industri_id', 'kategori_nama');

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . url('/kategori-industri/' . $row->kategori_industri_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                $btn .= '
                    <form action="' . url('/kategori-industri/' . $row->kategori_industri_id . '/delete') . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fas fa-trash"></i></button>
                    </form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $activeMenu = 'kategori-industri';

        $lastKode = KategoriIndustriModel::latest('kategori_industri_kode')->first()?->kategori_industri_kode ?? 'KI000';

        $nextNumber = (int) substr($lastKode, 2) + 1;
        $kodeKategori = 'KI' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin_page.kategori_industri.create', compact('activeMenu', 'kodeKategori'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kategori_industri_kode' => 'required|unique:m_kategori_industri,kategori_industri_kode',
            'kategori_nama' => 'required|string|max:255'
        ]);

        $last = KategoriIndustriModel::orderBy('kategori_industri_id', 'desc')->first();
        $lastNumber = $last ? intval(substr($last->kategori_industri_kode, 3)) : 0;
        $newKode = 'KAT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        try {
            KategoriIndustriModel::create([
                'kategori_industri_kode' => $newKode,
                'kategori_nama' => $request->kategori_nama
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori industri berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'msgField' => []
            ]);
        }
    }
    public function show($id)
    {
        $kategori = KategoriIndustriModel::findOrFail($id);
        $activeMenu = 'kategori-industri';
        return view('admin_page.kategori_industri.show', compact('kategori', 'activeMenu'));
    }

    public function edit($id)
    {
        $kategori = KategoriIndustriModel::findOrFail($id);
        $activeMenu = 'kategori-industri';
        return view('admin_page.kategori_industri.edit', compact('kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriIndustriModel::findOrFail($id);

        $request->validate([
            'kategori_nama' => 'required|unique:m_kategori_industri,kategori_nama,' . $id . ',kategori_industri_id'
        ]);

        $kategori->update([
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect()->route('kategori-industri.index')->with('success', 'Kategori industri berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kategori = KategoriIndustriModel::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori-industri.index')->with('success', 'Kategori industri berhasil dihapus');
    }
}
