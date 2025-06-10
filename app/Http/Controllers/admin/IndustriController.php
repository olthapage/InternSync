<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\IndustriModel;
use App\Models\KategoriIndustriModel;
use App\Models\KotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IndustriController extends Controller
{
    public function index()
    {
        $industri   = IndustriModel::with('kota', 'kategori_industri')->get();
        $activeMenu = 'industri';
        return view('admin_page.industri.index', compact('industri', 'activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = IndustriModel::with(['kota', 'kategori_industri'])->select('industri_id', 'industri_nama', 'kota_id', 'kategori_industri_id');

            if ($request->filled('kota_id')) {
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
                    $btn = '<button onclick="modalAction(\'' . url('/industri/' . $item->industri_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/industri/' . $item->industri_id . '/edit') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="deleteAction(\'' . url('/industri/' . $item->industri_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $kota       = KotaModel::all();
        $kategori   = KategoriIndustriModel::all();
        $activeMenu = 'industri';

        if ($request->ajax()) {
            return view('admin_page.industri.create', compact('kota', 'kategori'));
        }

        return view('admin_page.industri.create', compact('kota', 'kategori', 'activeMenu'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'industri_nama'        => 'required|string|max:255',
                    'kota_id'              => 'required|exists:m_kota,kota_id',
                    'kategori_industri_id' => 'required|exists:m_kategori_industri,kategori_industri_id',
                    'logo'                 => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'   => false,
                        'message'  => 'Validasi gagal',
                        'msgField' => $validator->errors(),
                    ]);
                }

                $data = $request->all();

                if ($request->hasFile('logo')) {
                    $file     = $request->file('logo');
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/logo_industri', $filename);
                    $data['logo'] = $filename;
                }

                IndustriModel::create($data);

                return response()->json([
                    'status'  => true,
                    'message' => 'Industri berhasil ditambahkan',
                ]);
            }

            return redirect()->route('industri.index');
        } catch (\Throwable $th) {
            Log::error('Error Store Industri: ' . $th->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada server: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $industri   = IndustriModel::with(['kota', 'kategori_industri'])->find($id);
        $activeMenu = 'industri';

        if ($request->ajax()) {
            return view('admin_page.industri.show', compact('industri', 'activeMenu'));
        }

        return view('admin_page.industri.show', compact('industri', 'activeMenu'));
    }

    public function edit(Request $request, $id)
    {
        $industri = IndustriModel::find($id);
        $kota     = KotaModel::all();
        $kategori = KategoriIndustriModel::all();

        if ($request->ajax()) {
            return view('admin_page.industri.edit', compact('industri', 'kota', 'kategori'));
        }

        $activeMenu = 'industri';
        return view('admin_page.industri.edit', compact('industri', 'kota', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
                'industri_nama'        => 'required|string|max:255',
                'kota_id'              => 'required|exists:m_kota,kota_id',
                'kategori_industri_id' => 'required|exists:m_kategori_industri,kategori_industri_id',
                'logo'                 => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                'email'                => 'required|email|max:255',
                'telepon'              => 'required|min:9|max:15',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $industri = IndustriModel::find($id);
            $data     = $request->all();

            // Jika upload logo baru
            if ($request->hasFile('logo')) {
                // Hapus file logo lama jika ada
                if ($industri->logo && Storage::exists('public/logo_industri/' . $industri->logo)) {
                    Storage::delete('public/logo_industri/' . $industri->logo);
                }

                // Simpan file logo baru
                $file         = $request->file('logo');
                $path         = $file->store('public/logo_industri');
                $filename     = basename($path);
                $data['logo'] = $filename;
            }

            $industri->update($data);

            return response()->json([
                'status'  => true,
                'message' => 'Industri berhasil diperbarui',
            ]);
        }

        return redirect()->route('industri.index');
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $industri = IndustriModel::find($id);
            $industri->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Industri berhasil dihapus.',
            ]);
        }

        return redirect()->route('industri.index');
    }
}
