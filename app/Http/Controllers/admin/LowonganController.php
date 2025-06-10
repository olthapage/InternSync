<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
        return view('admin_page.lowongan.index', [
            'activeMenu' => 'detail_lowongan',
            'lowongan'   => $lowongan,
            'industri'   => $industri,
        ]);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailLowonganModel::with('industri')
                ->select('lowongan_id', 'judul_lowongan', 'industri_id', 'tanggal_mulai');

            // Filter berdasarkan bulan periode
            if ($request->filled('filter_bulan')) {
                $data->whereMonth('tanggal_mulai', $request->filter_bulan);
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
                    // Gunakan helper route() agar lebih aman dan dinamis
                    $showUrl   = route('lowongan.show', $row->lowongan_id);
                    $editUrl   = route('lowongan.edit', $row->lowongan_id);
                    $deleteUrl = route('lowongan.delete.form', $row->lowongan_id); // URL untuk memuat modal

                    $btn = '<button onclick="modalAction(\'' . $showUrl . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . $deleteUrl . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $industri   = IndustriModel::all();
        $activeMenu = 'lowongan';
        if ($request->ajax()) {
            return view('admin_page.lowongan.create', compact('industri', 'activeMenu'));
        }
        $activeMenu = 'lowongan';
        return view('admin_page.lowongan.create', compact('industri', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'judul_lowongan' => 'required|string|max:255',
                'deskripsi'      => 'required|string',
                'industri_id'    => 'required|exists:m_industri,industri_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            DetailLowonganModel::create($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Lowongan berhasil ditambahkan',
            ]);
        }

        return redirect()->route('lowongan.index');
    }

    public function show(Request $request, $id)
    {
        $lowongan   = DetailLowonganModel::with(['industri'])->find($id);
        $activeMenu = 'lowongan';
        return view('admin_page.lowongan.show', compact('lowongan', 'activeMenu'));

        if ($request->ajax()) {
            return view('lowongan.show', compact('lowongan', 'activeMenu'));
        }
    }

    public function edit(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::find($id);
        $industri = IndustriModel::all();
        if ($request->ajax()) {
            return view('admin_page.lowongan.edit', compact('lowongan', 'industri'));
        }
        $activeMenu = 'lowongan';

        return view('admin_page.lowongan.edit', compact('lowongan', 'industri', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'judul_lowongan' => 'required|string|max:255',
                'deskripsi'      => 'required|string',
                'industri_id'    => 'required|exists:m_industri,industri_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $lowongan = DetailLowonganModel::find($id);

            $lowongan->update($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Lowongan berhasil diperbarui',
            ]);
        }

        return redirect()->route('lowongan.index');
    }

    public function delete($id)
    {
        // LOG 1: Cek apakah method ini terpanggil dengan ID yang benar
        Log::info("Mencoba memuat modal hapus untuk Lowongan ID: {$id}");

        try {
            $lowongan = DetailLowonganModel::with('industri')->find($id);

            // LOG 2: Cek apakah data lowongan ditemukan atau tidak
            if (! $lowongan) {
                Log::error("Data Lowongan dengan ID: {$id} tidak ditemukan saat akan memuat modal hapus.");
                // Jika tidak ditemukan, kembalikan view error agar jelas di modal
                return view('admin_page.errors.modal_not_found', ['message' => 'Data lowongan yang akan dihapus tidak ditemukan.']);
            }

            // LOG 3: Jika berhasil, catat sebelum menampilkan view
            Log::info("Data ditemukan: '{$lowongan->judul_lowongan}'. Menampilkan view 'admin_page.lowongan.delete'.");
            return view('admin_page.lowongan.delete', compact('lowongan'));

        } catch (\Exception $e) {
            // LOG 4: Tangkap jika ada error tak terduga lainnya
            Log::error("Terjadi error di method delete LowonganController: " . $e->getMessage());
            return view('admin_page.errors.modal_error', ['message' => 'Terjadi kesalahan pada server.']);
        }
    }

    public function destroy($id)
    {
        // Gunakan try-catch untuk penanganan error yang lebih baik
        try {
            $lowongan = DetailLowonganModel::findOrFail($id);
            $lowongan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Lowongan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error (misal: lowongan tidak ditemukan atau masalah database)
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }
}
