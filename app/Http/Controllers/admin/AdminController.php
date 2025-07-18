<?php

namespace App\Http\Controllers\admin;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::get();
        $activeMenu = 'admin';
        return view('admin_page.admin.index', compact('admin', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'nama_lengkap', 'email');

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($u) {
                $btn  = '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/show") . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/edit") . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url("/admin/{$u->user_id}/delete") . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin_page.admin.create');
        }
        $activeMenu = 'admin';
        return view('admin_page.admin.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_user,email',
                'telepon'      => 'required|min:9|max:15',
                'password'     => 'required|min:6',
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
                'telepon'      => $request->telepon,
                'password' => Hash::make($request->password),
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
            $admin = UserModel::find($id);

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
        if ($request->ajax()) {
            return view('admin_page.admin.edit', compact('admin'));
        }
        $activeMenu = 'admin';
        return view('admin_page.admin.edit', compact('admin', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_user,email,' . $id . ',user_id',
                'telepon'      => 'required|min:9|max:15',
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
                $data = $request->only(['nama_lengkap', 'email', 'telepon']);

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
        $admin = UserModel::findOrFail($id);
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
