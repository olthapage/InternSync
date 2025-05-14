<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::with('level')->get();
        $activeMenu = 'admin';
        return view('admin.index', compact('admin', 'activeMenu'));
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
            return view('admin.create', compact('level'));
        }
        $activeMenu = 'admin';
        return view('admin.create', compact('level', 'activeMenu'));
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
        if ($request->ajax()) {
            return view('admin.show', compact('admin'));
        }
        return redirect()->route('admin.index');
    }

    public function edit(Request $request, $id)
    {
        $admin = UserModel::findOrFail($id);
        $level = LevelModel::all();
        if ($request->ajax()) {
            return view('admin.edit', compact('admin', 'level'));
        }
        $activeMenu = 'admin';
        return view('admin.edit', compact('admin', 'level', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()|| $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_user,email,' . $id . ',user_id',
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

            $admin = UserModel::find($id);
            if ($admin) {
                $data = $request->only(['nama_lengkap', 'email', 'level_id']);
                if ($request->filled('password')) {
                    $data['password'] = $request->password;
                }
                $admin->update($data);
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
        return view('admin.delete', compact('admin'));
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
