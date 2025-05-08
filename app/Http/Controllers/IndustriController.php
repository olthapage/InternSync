<?php

namespace App\Http\Controllers;

use Log;
use App\Models\KotaModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\KategoriIndustriModel;
use Yajra\DataTables\Facades\DataTables;

class IndustriController extends Controller
{
    public function index()
    {
        $industri = IndustriModel::with(['kota', 'kategori_industri'])->get();
        $activeMenu = 'industri';
        return view('industri.index', compact('industri', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $query = IndustriModel::select(
                'industri_id',
                'industri_nama',
                'kota_id',
                'kategori_industri_id',
            )
            ->with(['kota', 'kategori_industri']);


        if ($request->kota_id) {
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
                $btn  = '<a href="' . url('/industri/' . $item->industri_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/industri/' . $item->industri_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '
                    <form action="' . url('/industri/' . $item->industri_id . '/delete') . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>
                    </form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $kota = KotaModel::all();
        $kategori = KategoriIndustriModel::all();
        $activeMenu = 'industri';
        return view('industri.create', compact('kota', 'kategori', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'industri_nama' => 'required',
            'kota_id' => 'required',
            'kategori_industri_id' => 'required',
        ]);

        IndustriModel::create($request->only(['industri_nama', 'kota_id', 'kategori_industri_id']));

        return redirect()->route('industri.index')->with('success', 'Industri berhasil ditambahkan');
    }

    public function show($id)
    {
        $industri = IndustriModel::with(['kota', 'kategori_industri'])->findOrFail($id);
        $activeMenu = 'industri';
        return view('industri.show', compact('industri', 'activeMenu'));
    }

    public function edit($id)
    {
        $industri = IndustriModel::findOrFail($id);
        $kota = KotaModel::all();
        $kategori = KategoriIndustriModel::all();
        $activeMenu = 'industri';
        return view('industri.edit', compact('industri', 'kota', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'industri_nama' => 'required',
            'kota_id' => 'required',
            'kategori_industri_id' => 'required',
        ]);

        $industri = IndustriModel::findOrFail($id);
        $industri->update($request->only(['industri_nama', 'kota_id', 'kategori_industri_id']));

        return redirect()->route('industri.index')->with('success', 'Industri berhasil diperbarui');
    }

    public function destroy($id)
    {
        $industri = IndustriModel::findOrFail($id);
        $industri->delete();

        return redirect()->route('industri.index')->with('success', 'Industri berhasil dihapus');
    }
}
