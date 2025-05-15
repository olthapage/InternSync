<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Validator;
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
                $btn  = '<button onclick="modalAction(\'' . url('/lowongan/' . $row->lowongan_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lowongan/' . $row->lowongan_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lowongan/' . $row->lowongan_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }
    public function create(Request $request)
    {
        $industri = IndustriModel::all();
        $activeMenu = 'lowongan';
        if ($request->ajax()) {
        return view('lowongan.create', compact('industri', 'activeMenu'));
    }
        $activeMenu = 'lowongan';
        return view('lowongan.create', compact('industri', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
        $validator = Validator::make($request->all(), [
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'industri_id' => 'required|exists:m_industri,industri_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        DetailLowonganModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Lowongan berhasil ditambahkan'
        ]);
    }

    return redirect()->route('lowongan.index');
}

    public function show(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::with(['industri'])->find($id);
        $activeMenu = 'lowongan';
        return view('lowongan.show', compact('lowongan', 'activeMenu'));

         if ($request->ajax()) {
             return view('lowongan.show', compact('lowongan', 'activeMenu'));
         }
    }

    public function edit(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::find($id);
        $industri = IndustriModel::all();
        if ($request->ajax()) {
             return view('lowongan.edit', compact('lowongan', 'industri'));
         }
        $activeMenu = 'lowongan';

        return view('lowongan.edit', compact('lowongan', 'industri', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'industri_id' => 'required|exists:m_industri,industri_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $lowongan = DetailLowonganModel::find($id);

        $lowongan->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Lowongan berhasil diperbarui'
        ]);
    }

        return redirect()->route('lowongan.index');
    }


    public function delete_ajax(Request $request, $id)
    {
        if (request()->ajax()) {
            $lowongan = DetailLowonganModel::find($id);
            $lowongan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Lowongan berhasil dihapus.'
        ]);
    }

    return redirect()->route('lowongan.index');
}
}