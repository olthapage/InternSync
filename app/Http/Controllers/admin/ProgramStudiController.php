<?php

namespace App\Http\Controllers\admin;

use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
                    // PERBAIKAN: Tombol hapus sekarang memanggil modalAction
                    $showUrl = route('program-studi.show', $item->prodi_id);
                    $editUrl = route('program-studi.edit', $item->prodi_id);
                    // URL baru untuk menampilkan modal konfirmasi hapus
                    $deleteShowUrl = route('program-studi.delete.show', $item->prodi_id);

                    $btn  = '<button onclick="modalAction(\'' . $showUrl . '\')" class="btn btn-info btn-sm" title="Lihat Detail"><i class="fas fa-eye"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . $deleteShowUrl . '\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>';
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
        // 2. Log saat fungsi dimulai dan catat data yang masuk
        Log::info('Memulai proses store Program Studi.');
        Log::debug('Data Request:', $request->all());

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'nama_prodi' => 'required|string|max:100',
                'kode_prodi' => 'required|string|max:20|unique:tabel_prodi,kode_prodi',
            ]);

            if ($validator->fails()) {
                // 3. Log jika validasi gagal
                Log::warning('Validasi gagal untuk store Program Studi.', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // 4. Gunakan try-catch untuk menangani error saat menyimpan ke database
            try {
                $prodi = ProdiModel::create($request->only('nama_prodi', 'kode_prodi'));

                // 5. Log jika berhasil disimpan
                Log::info('Program Studi berhasil dibuat dengan ID: ' . $prodi->prodi_id);

                return response()->json([
                    'status' => true,
                    'message' => 'Program Studi berhasil ditambahkan.'
                ]);

            } catch (\Exception $e) {
                // 6. Log jika terjadi error saat proses create
                Log::error('Gagal membuat Program Studi di database: ' . $e->getMessage());

                // Kembalikan respons error server
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan pada server. Gagal menyimpan data.'
                ], 500); // HTTP 500 Internal Server Error
            }
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

    public function showDeleteForm($id)
    {
        $prodi = ProdiModel::find($id);
        return view('admin_page.program_studi.delete', compact('prodi'));
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
