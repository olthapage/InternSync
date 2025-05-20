<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LowonganController extends Controller
{
    public function index()
    {
        $activeMenu = 'lowongan';
        $lowongan = DetailLowonganModel::latest()->get();

        return view('mahasiswa_page.lowongan.index', compact('activeMenu', 'lowongan'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailLowonganModel::select('lowongan_id', 'judul_lowongan', 'slot', 'tanggal_mulai', 'tanggal_selesai');

            if ($request->filled('filter_bulan')) {
                $data->whereMonth('tanggal_mulai', $request->filter_bulan);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('periode', function ($row) {
                    return date('d/m/Y', strtotime($row->tanggal_mulai)) . ' - ' . date('d/m/Y', strtotime($row->tanggal_selesai));
                })
                ->addColumn('aksi', function ($row) {
                    $btn  = '<button onclick="modalAction(\'' . url('/mahasiswa/lowongan/' . $row->lowongan_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create()
    {
        $activeMenu = 'lowongan';
        return view('mahasiswa_page.lowongan.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_lowongan'   => 'required|string|max:255',
            'slot'             => 'required|integer|min:1',
            'deskripsi'        => 'required|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DetailLowonganModel::create($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);

        if ($request->ajax()) {
            return view('mahasiswa_page.lowongan.edit', compact('lowongan'));
        }

        $activeMenu = 'lowongan';
        return view('mahasiswa_page.lowongan.edit', compact('lowongan', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul_lowongan'   => 'required|string|max:255',
            'slot'             => 'required|integer|min:1',
            'deskripsi'        => 'required|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lowongan->update($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = DetailLowonganModel::find($id);
        if (! $lowongan) {
            return response()->json([
                'status'  => false,
                'message' => 'Lowongan tidak ditemukan.',
            ]);
        }

        $lowongan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lowongan berhasil dihapus.',
        ]);
    }

    public function deleteModal($id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);
        return view('mahasiswa_page.lowongan.delete', compact('lowongan'));
    }
}
