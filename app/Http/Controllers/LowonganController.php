<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use App\Models\KategoriLowonganModel;
use Yajra\DataTables\Facades\DataTables;

class LowonganController extends Controller
{
    /**
     * Display a listing of industries with the count of lowongan for each.
     *
     * @return \Illuminate\Http\Response
     *  @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lowongan = DetailLowonganModel::all();
        $industri = IndustriModel::with('detail_lowongan')->get();
        return view('lowongan.index', [
            'activeMenu' => 'detail_lowongan',
            'lowongan' => $lowongan,
            'industri' => $industri
        ]);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailLowonganModel::with('industri')->select('lowongan_id', 'judul_lowongan', 'industri_id');

            if ($request->filled('filter_lowongan')) {
                $data->where('lowongan_id', $request->filter_lowongan);
            }

            if ($request->filled('filter_industri')) {
                $data->where('industri_id', $request->filter_industri);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('industri_nama', function ($row) {
                    return $row->industri ? $row->industri->industri_nama : '-';
                })
                ->addColumn('aksi', function ($row) {
                    $btn  = '<a href="' . url('/lowongan/' . $row->lowongan_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                    $btn .= '<a href="' . url('/lowongan/' . $row->industri_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                    $btn .= '
                        <form action="' . url('/lowongan/' . $row->industri_id . '/delete') . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>
                        </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }
    public function create()
    {
        $industri = IndustriModel::all();
        $activeMenu = 'lowongan';

        return view('lowongan.create', compact('industri', 'activeMenu'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'industri_id' => 'required|exists:m_industri,industri_id',
        ]);

        DetailLowonganModel::create($validated);

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lowongan = DetailLowonganModel::with(['industri'])->findOrFail($id);
        $activeMenu = 'lowongan';
        return view('lowongan.show', compact('lowongan', 'activeMenu'));
    }

    public function edit($id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);
        $industri = IndustriModel::all();
        $activeMenu = 'lowongan';
        return view('lowongan.edit', compact('lowongan', 'industri', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);

        $validated = $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'industri_id' => 'required|exists:m_industri,industri_id',
        ]);

        $lowongan->update($validated);

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DetailLowonganModel::findOrFail($id)->delete();
        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
