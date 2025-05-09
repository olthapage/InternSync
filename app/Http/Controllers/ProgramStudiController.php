<?php

namespace App\Http\Controllers;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $data = ProdiModel::all();
        $activeMenu = 'prodi';
        return view('program_studi.index', compact('data','activeMenu' ));
    }

    public function list(Request $request)
    {
    $data = ProdiModel::select(
            'prodi_id',
            'nama_prodi',
            'kode_prodi',
        );

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function ($prodi) {
            $detail = '<a href="' . url('/program-studi/' . $prodi->prodi_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
            $edit = '<a href="' . url('/program-studi/' . $prodi->prodi_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            $hapus = '<button class="btn btn-danger btn-sm btn-hapus" data-id="' . $prodi->prodi_id . '">Hapus</button>';
            return $detail . $edit . $hapus;
        })
        ->rawColumns(['aksi']) // kolom 'aksi' berisi HTML
        ->make(true);
    }

    public function create()
    {
        $activeMenu = 'prodi';
        return view('program_studi.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'nama_prodi' => 'required|string|max:100',
        'kode_prodi' => 'required|string|max:20|unique:prodi,kode_prodi',
    ]);

    ProdiModel::create([
        'nama_prodi' => $request->nama_prodi,
        'kode_prodi' => $request->kode_prodi,
    ]);

    return redirect()->route('program-studi.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $prodi = ProdiModel::findOrFail($id);
        return view('program_studi.show', compact('activeMenu'));
    }

    public function edit($id)
    {
    $prodi = ProdiModel::findOrFail($id);
    $activeMenu = 'prodi';
    return view('program_studi.edit', compact('prodi', 'activeMenu'));   
    }

    public function update(Request $request, $id)
    {
    $prodi = ProdiModel::findOrFail($id);

    $request->validate([
        'nama_prodi' => 'required|string|max:100',
        'kode_prodi' => 'required|string|max:20|unique:prodi,kode_prodi,' . $id . ',prodi_id',
    ]);

    $prodi->update([
        'nama_prodi' => $request->nama_prodi,
        'kode_prodi' => $request->kode_prodi,
    ]);

    return redirect()->route('program-studi.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prodi = ProdiModel::findOrFail($id);
        $prodi->delete();

    return response()->json(['success' => true, 'message' => 'Program studi berhasil dihapus.']);
    }
}
