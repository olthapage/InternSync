<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use App\Models\IndustriModel;
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
        $industries = IndustriModel::all();
        return view('lowongan.index', [
            'activeMenu' => 'detail_lowongan',
            'industries' => $industries
        ]);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = IndustriModel::with(['detail_lowongan' => function ($query) {
                $query->select('judul_lowongan', 'industri_id');
            }])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('industri_nama', function ($row) {
                    return $row->industri_nama;
                })
                ->addColumn('judul_lowongan', function ($row) {
                    return $row->detail_lowongan->pluck('judul_lowongan')->implode(', ');
                })
                ->addColumn('aksi', function ($row) {
                    return '<button class="btn btn-sm btn-info">Detail</button>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }
}
