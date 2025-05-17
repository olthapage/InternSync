<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::with('prodi')->get();
        $activeMenu = 'mahasiswa';
        return view('mahasiswa.index', compact('mahasiswa', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $users = MahasiswaModel::select(
            'mahasiswa_id',
            'nama_lengkap',
            'email',
            'ipk',
            'nim',
            'status',
            'level_id',
            'prodi_id',
            'dosen_id'
        )->with(['level', 'prodi', 'dosen']);

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level', fn($user) => $user->level->level_nama ?? '-')
            ->addColumn('prodi', fn($user) => $user->prodi->nama_prodi ?? '-')
            ->addColumn('dosen', fn($user) => $user->dosen->nama_lengkap ?? '-')
            ->addColumn('aksi', function ($user) {
                $btn  = '<button onclick="modalAction(\'' . url('/mahasiswa/' . $user->mahasiswa_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $user->mahasiswa_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $user->mahasiswa_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        $dosen = DosenModel::all();

        if ($request->ajax()) {
            return view('mahasiswa.create', compact('prodi', 'level', 'dosen'));
        }

        $activeMenu = 'mahasiswa';
        return view('mahasiswa.create', compact('prodi', 'level', 'dosen', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:m_mahasiswa,email',
                'password' => 'required|min:6',
                'ipk' => 'nullable|numeric|min:0|max:4',
                'nim' => 'required|unique:m_mahasiswa,nim',
                'status' => 'required|boolean',
                'level_id' => 'required',
                'prodi_id' => 'required',
                'dosen_id' => 'nullable'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            MahasiswaModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'ipk' => $request->ipk,
                'nim' => $request->nim,
                'status' => $request->status,
                'level_id' => $request->level_id,
                'prodi_id' => $request->prodi_id,
                'dosen_id' => $request->dosen_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Mahasiswa berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    public function show(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'level', 'dosen', 'preferensiLokasi', 'skills'])->find($id);

        if ($request->ajax()) {
            return view('mahasiswa.show', compact('mahasiswa'));
        }

        return redirect('/');
    }

    public function edit(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        $dosen = DosenModel::all();

        if ($request->ajax()) {
            return view('mahasiswa.edit', compact('mahasiswa', 'prodi', 'level', 'dosen'));
        }

        $activeMenu = 'mahasiswa';
        return view('mahasiswa.edit', compact('mahasiswa', 'prodi', 'level', 'dosen', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Data received:', $request->all());
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_mahasiswa,email,' . $id . ',mahasiswa_id',
                'ipk'          => 'nullable|numeric|min:0|max:4',
                'nim'          => 'required|unique:m_mahasiswa,nim,' . $id . ',mahasiswa_id',
                'status'       => 'required|boolean',
                'level_id'     => 'required',
                'prodi_id'     => 'required',
                'dosen_id'     => 'nullable',
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

            $mahasiswa = MahasiswaModel::find($id);
            if ($mahasiswa) {
                $data = $request->only(['nama_lengkap', 'email', 'ipk', 'nim', 'status', 'level_id', 'prodi_id', 'dosen_id']);

                if ($request->filled('password')) {
                    $data['password'] = bcrypt($request->password);
                }

                // Create foto directory if it doesn't exist
                if (!Storage::disk('public')->exists('foto')) {
                    Storage::disk('public')->makeDirectory('foto');
                    Log::info('Created foto directory in storage');
                }

                // Debug storage permissions
                Log::info('Storage permissions check:', [
                    'public_writable' => is_writable(storage_path('app/public')),
                    'foto_writable' => is_writable(storage_path('app/public/foto')),
                    'storage_path' => storage_path('app/public/foto')
                ]);

                // Detailed logging of file upload request
                Log::info('File Upload Request Details:', [
                    'hasFile' => $request->hasFile('foto'),
                    'allFiles' => $request->allFiles(),
                    'fileInput_name' => 'foto',
                    'request_method' => $request->method(),
                    'content_type' => $request->header('Content-Type'),
                    'enctype' => $request->header('Content-Type') ? str_contains($request->header('Content-Type'), 'multipart/form-data') : false
                ]);

                // Check if there's a file in the request
                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');

                    // Log details about the uploaded file
                    Log::info('Uploaded File Details:', [
                        'isValid' => $file->isValid(),
                        'originalName' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'mimeType' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'error' => $file->getError(),
                        'error_message' => $file->getErrorMessage()
                    ]);

                    if ($file->isValid()) {
                        try {
                            // Delete old file if exists
                            if ($mahasiswa->foto && Storage::disk('public')->exists('foto/' . $mahasiswa->foto)) {
                                Storage::disk('public')->delete('foto/' . $mahasiswa->foto);
                                Log::info('Old photo deleted', ['old_file' => $mahasiswa->foto]);
                            }

                            // Generate unique filename
                            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                            // Store file with direct file access to avoid issues
                            $uploadSuccess = $file->move(storage_path('app/public/foto'), $namaFile);

                            if ($uploadSuccess) {
                                $data['foto'] = $namaFile;
                                Log::info('File uploaded successfully using move method', [
                                    'filename' => $namaFile,
                                    'path' => storage_path('app/public/foto/' . $namaFile),
                                    'exists' => file_exists(storage_path('app/public/foto/' . $namaFile))
                                ]);
                            } else {
                                Log::error('Failed to move file');
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan foto (move failed)'
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Exception during file upload:', [
                                'message' => $e->getMessage(),
                                'code' => $e->getCode(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'trace' => $e->getTraceAsString()
                            ]);

                            return response()->json([
                                'status' => false,
                                'message' => 'Gagal menyimpan foto: ' . $e->getMessage()
                            ]);
                        }
                    } else {
                        Log::error('Invalid file upload:', [
                            'error_code' => $file->getError(),
                            'error_message' => $file->getErrorMessage()
                        ]);

                        return response()->json([
                            'status' => false,
                            'message' => 'File tidak valid: ' . $file->getErrorMessage()
                        ]);
                    }
                }

                // Update mahasiswa data
                $mahasiswa->update($data);

                // Log successful update
                Log::info('Mahasiswa updated successfully:', [
                    'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                    'foto' => $mahasiswa->foto,
                    'foto_path' => $mahasiswa->foto ? asset('storage/foto/' . $mahasiswa->foto) : null,
                    'raw_data' => $data
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data mahasiswa berhasil diperbarui'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ]);
        }

        return redirect('/');
    }

    public function deleteModal(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'level', 'dosen'])->find($id);
        return view('mahasiswa.delete', compact('mahasiswa'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('mahasiswa.index');
        }

        $mahasiswa = MahasiswaModel::find($id);
        if ($mahasiswa) {
            $mahasiswa->delete();
            return response()->json([
                'status' => true,
                'message' => 'Mahasiswa berhasil dihapus'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data mahasiswa tidak ditemukan'
        ]);
    }
}
