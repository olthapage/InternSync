<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::with('level')->get();
        $activeMenu = 'admin';
        return view('admin_page.admin.index', compact('admin', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'nama_lengkap', 'email', 'level_id')
            ->with('level');

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level', fn($u) => $u->level->level_nama ?? '-')
            ->addColumn('aksi', function ($u) {
                $btn  = '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/show") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/edit") . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/delete") . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $level = LevelModel::all();
        if ($request->ajax()) {
            return view('admin_page.admin.create', compact('level'));
        }
        $activeMenu = 'admin';
        return view('admin_page.admin.create', compact('level', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_user,email',
                'password'     => 'required|min:6',
                'level_id'     => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            UserModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'password'     => bcrypt($request->password),
                'level_id'     => $request->level_id,
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'Admin berhasil ditambahkan'
            ]);
        }
        return redirect()->route('admin.index');
    }

    public function show(Request $request, $id)
        {
            $admin = UserModel::with('level')->find($id);

        \Log::info('Foto Admin:', [
            'foto' => $admin->foto,
            'path_exists' => $admin->foto ? \Storage::exists('public/foto/' . $admin->foto) : false
        ]);

            if ($request->ajax()) {
                return view('admin_page.admin.show', compact('admin'));
            }
            return redirect()->route('admin.index');
        }


    public function edit(Request $request, $id)
    {
        $admin = UserModel::findOrFail($id);
        $level = LevelModel::all();
        if ($request->ajax()) {
            return view('admin_page.admin.edit', compact('admin', 'level'));
        }
        $activeMenu = 'admin';
        return view('admin_page.admin.edit', compact('admin', 'level', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_user,email,' . $id . ',user_id',
                'level_id'     => 'required',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $admin = UserModel::find($id);
            if ($admin) {
                $data = $request->only(['nama_lengkap', 'email', 'level_id']);

                if ($request->filled('password')) {
                    $data['password'] = bcrypt($request->password);
                }

                // Debug file upload
                Log::info('Request Foto Details:', [
                    'hasFile' => $request->hasFile('foto'),
                    'isValid' => $request->hasFile('foto') ? $request->file('foto')->isValid() : false,
                    'fileExists' => $request->file('foto') ? true : false,
                    'fileName' => $request->file('foto') ? $request->file('foto')->getClientOriginalName() : null,
                    'fileSize' => $request->file('foto') ? $request->file('foto')->getSize() : null,
                ]);

                // handle foto
                if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                    // Hapus file lama jika ada
                    if ($admin->foto && Storage::disk('public')->exists('foto/' . $admin->foto)) {
                        Storage::disk('public')->delete('foto/' . $admin->foto);
                    }

                    $file = $request->file('foto');
                    $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Try-catch for better error handling
                    try {
                        $path = $file->storeAs('foto', $namaFile, 'public');
                        $data['foto'] = $namaFile;

                        // Log successful file upload
                        Log::info('Foto berhasil disimpan', [
                            'namaFile' => $namaFile,
                            'path' => $path,
                            'full_path' => Storage::disk('public')->path('foto/' . $namaFile),
                            'exists' => Storage::disk('public')->exists('foto/' . $namaFile)
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error saat menyimpan foto', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);

                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal menyimpan foto: ' . $e->getMessage()
                        ]);
                    }
                } else if ($request->hasFile('foto')) {
                    // Log invalid file
                    Log::error('File foto tidak valid', [
                        'errors' => $request->file('foto')->getErrorMessage()
                    ]);

                    return response()->json([
                        'status' => false,
                        'message' => 'File foto tidak valid'
                    ]);
                }

                $admin->update($data);

                Log::info('Admin berhasil diperbarui', [
                    'user_id' => $admin->user_id,
                    'data' => $data,
                    'foto_sekarang' => $admin->foto
                ]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Admin berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data admin tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }


    public function deleteModal(Request $request, $id)
    {
        $admin = UserModel::with('level')->findOrFail($id);
        return view('admin_page.admin.delete', compact('admin'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('admin.index');
        }
        $admin = UserModel::find($id);
        if ($admin) {
            $admin->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Admin berhasil dihapus'
            ]);
        }
        return response()->json([
            'status'  => false,
            'message' => 'Data admin tidak ditemukan'
        ]);
    }
}
