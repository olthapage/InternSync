<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $data = ProdiModel::all();
        $activeMenu = 'prodi';
        return view('admin_page.program_studi.index', compact('data','activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = ProdiModel::select('prodi_id', 'nama_prodi', 'kode_prodi');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function ($item) {
                    $btn  = '<button onclick="modalAction(\'' . url('/program-studi/' . $item->prodi_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/program-studi/' . $item->prodi_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/program-studi/' . $item->prodi_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $activeMenu = 'prodi';
        $prodi = ProdiModel::all();
        return view('admin_page.program_studi.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => 'required|string|max:100',
            'kode_prodi' => 'required|string|max:20|unique:tabel_prodi,kode_prodi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        ProdiModel::create($request->only('nama_prodi', 'kode_prodi'));

        return response()->json([
            'status' => true,
            'message' => 'Program Studi berhasil ditambahkan.'
        ]);
    }

        return redirect()->route('program-studi.index');
    }

    public function show(Request $request, $id)
    {
        $prodi = ProdiModel::find($id);
        $activeMenu = 'prodi';

        if ($request->ajax()) {
            return view('admin_page.program_studi.show', compact('prodi'));
        }

        return view('admin_page.program_studi.show', compact('prodi', 'activeMenu'));
    }

    public function edit(Request $request, $id)
    {
        $prodi = ProdiModel::find($id);
        $activeMenu = 'prodi';

        if ($request->ajax()) {
            return view('admin_page.program_studi.edit', compact('prodi'));
        }

        return view('admin_page.program_studi.edit', compact('prodi', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
            'nama_prodi' => 'required|string|max:100',
            'kode_prodi' => 'required|string|max:20|unique:tabel_prodi,kode_prodi,' . $id . ',prodi_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $prodi = ProdiModel::find($id);
        $prodi->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Program Studi berhasil diperbarui.'
        ]);
    }
        return redirect()->route('program_studi.index');
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $prodi = ProdiModel::find($id);
            $prodi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil dihapus.'
            ]);
        }

        return redirect()->route('program_studi.index');
    }
}
