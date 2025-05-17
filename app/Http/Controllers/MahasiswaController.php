<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::with('prodi')->get();
        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.index', compact('mahasiswa', 'activeMenu'));
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
            return view('admin_page.mahasiswa.create', compact('prodi', 'level', 'dosen'));
        }

        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.create', compact('prodi', 'level', 'dosen', 'activeMenu'));
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
            return view('admin_page.mahasiswa.show', compact('mahasiswa'));
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
            return view('admin_page.mahasiswa.edit', compact('mahasiswa', 'prodi', 'level', 'dosen'));
        }

        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.edit', compact('mahasiswa', 'prodi', 'level', 'dosen', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:m_mahasiswa,email,' . $id . ',mahasiswa_id',
                'ipk' => 'nullable|numeric|min:0|max:4',
                'nim' => 'required|unique:m_mahasiswa,nim,' . $id . ',mahasiswa_id',
                'status' => 'required|boolean',
                'level_id' => 'required',
                'prodi_id' => 'required',
                'dosen_id' => 'nullable'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $mahasiswa = MahasiswaModel::find($id);
            if ($mahasiswa) {
                $data = $request->only(['nama_lengkap', 'email', 'ipk', 'nim', 'status', 'level_id', 'prodi_id', 'dosen_id']);
                if ($request->filled('reset_password') && $request->reset_password == "1") {
                    $data['password'] = bcrypt($request->nim);
                }
                $mahasiswa->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Mahasiswa berhasil diperbarui'
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
        return view('admin_page.mahasiswa.delete', compact('mahasiswa'));
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
