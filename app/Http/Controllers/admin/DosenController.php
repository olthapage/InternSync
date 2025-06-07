<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::with('prodi')->get();
        $activeMenu = 'dosen';
        return view('admin_page.dosen.index', compact('dosen', 'activeMenu'));
    }
    public function list(Request $request)
    {
        $users = DosenModel::select(
            'dosen_id',
            'nama_lengkap',
            'email',
            'nip',
            'level_id',
            'prodi_id',
        )
            ->with(['level', 'prodi']);

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level', function ($user) {
                return $user->level->level_nama ?? '-';
            })
            ->addColumn('prodi', function ($user) {
                return $user->prodi->nama_prodi ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn  = '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create(Request $request)
    {
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        if ($request->ajax()) {
            return view('admin_page.dosen.create', compact('prodi', 'level'));
        }
        $activeMenu = 'dosen';
        return view('admin_page.dosen.create', compact('prodi', 'level', 'activeMenu'));
    }
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:m_dosen,email',
                'telepon' => 'required|min:9|max:15',
                'password' => 'required|min:6',
                'nip' => 'required|unique:m_dosen,nip',
                'level_id' => 'required',
                'prodi_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            DosenModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'password' => $request->password,
                'nip' => $request->nip,
                'level_id' => $request->level_id,
                'prodi_id' => $request->prodi_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Dosen berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    public function show(Request $request, $id)
    {
        $dosen = DosenModel::with(['prodi', 'level'])->find($id);

        if ($request->ajax()) {
            return view('admin_page.dosen.show', compact('dosen'));
        }
    }

    public function edit(Request $request, $id)
    {
        $dosen = DosenModel::findOrFail($id);
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        if ($request->ajax()) {
            return view('admin_page.dosen.edit', compact('dosen', 'prodi', 'level'));
        }
        $activeMenu = 'dosen';
        return view('admin_page.dosen.edit', compact('dosen', 'prodi', 'level', 'activeMenu'));
    }
    public function update(Request $request, $id)
{
    Log::info('Data Dosen diterima untuk update:', $request->all());

    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'nama_lengkap' => 'required',
            'email'        => 'required|email|unique:m_dosen,email,' . $id . ',dosen_id',
            'telepon'      => 'required|min:9|max:15',
            'nip'          => 'required|unique:m_dosen,nip,' . $id . ',dosen_id',
            'level_id'     => 'required',
            'prodi_id'     => 'required',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $dosen = DosenModel::find($id);
        if ($dosen) {
            $data = $request->only(['nama_lengkap', 'email', 'telepon', 'nip', 'level_id', 'prodi_id']);

            if ($request->filled('reset_password') && $request->reset_password == "1") {
                $data['password'] = bcrypt($request->nip);
            }

            if (!Storage::disk('public')->exists('foto')) {
                Storage::disk('public')->makeDirectory('foto');
                Log::info('Folder "foto" dibuat di penyimpanan publik');
            }

            Log::info('Detil File Upload:', [
                'hasFile' => $request->hasFile('foto'),
                'fileDetails' => $request->hasFile('foto') ? [
                    'originalName' => $request->file('foto')->getClientOriginalName(),
                    'mimeType' => $request->file('foto')->getMimeType(),
                    'size' => $request->file('foto')->getSize(),
                ] : null
            ]);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                if ($file->isValid()) {
                    try {
                        if ($dosen->foto && Storage::disk('public')->exists('foto/' . $dosen->foto)) {
                            Storage::disk('public')->delete('foto/' . $dosen->foto);
                            Log::info('Foto lama dosen dihapus', ['file' => $dosen->foto]);
                        }

                        $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $uploadSuccess = $file->move(storage_path('app/public/foto'), $namaFile);

                        if ($uploadSuccess) {
                            $data['foto'] = $namaFile;
                            Log::info('Foto dosen berhasil diupload', [
                                'filename' => $namaFile,
                                'path' => storage_path('app/public/foto/' . $namaFile),
                                'exists' => file_exists(storage_path('app/public/foto/' . $namaFile))
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Gagal menyimpan foto (move failed)'
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Kesalahan saat upload foto dosen:', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);

                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal menyimpan foto: ' . $e->getMessage()
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'File foto tidak valid: ' . $file->getErrorMessage()
                    ]);
                }
            }

            $dosen->update($data);

            Log::info('Dosen berhasil diperbarui:', [
                'dosen_id' => $dosen->dosen_id,
                'foto' => $dosen->foto,
                'data' => $data
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil diperbarui'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data dosen tidak ditemukan'
        ]);
    }

    return redirect('/');
}

    public function deleteModal(Request $request, $id)
    {
        $dosen = DosenModel::with(['prodi', 'level'])->find($id);
        return view('dosen.delete', compact('dosen'));
    }
    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('dosen.index');
        }
        $dosen = DosenModel::find($id);
        if ($dosen) {
            $dosen->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Dosen berhasil dihapus'
            ]);
        }
        return response()->json([
            'status'  => false,
            'message' => 'Data dosen tidak ditemukan'
        ]);
    }
}
