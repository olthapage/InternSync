<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::all();
        $activeMenu = 'admin';
        return view('admin.index', compact('admin', 'activeMenu'));
    }
    public function list(Request $request)
    {
        $users = UserModel::select(
                'user_id',
                'nama_lengkap',
                'email',
                'level_id',
            )->with(['level']);

        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level', function ($user) {
                return $user->level->level_nama ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn  = '<a href="' . url('/admin/' . $user->user_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/admin/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '
                    <form action="' . url('/admin/' . $user->user_id . '/delete') . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>
                    </form>';
                return $btn;

            })
            ->rawColumns(['aksi']) // Beri tahu bahwa kolom 'aksi' berisi HTML
            ->make(true);
    }
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_user,email',
            'password' => 'required|min:6',
            'level_id' => 'required',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        UserModel::create($validated);
        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan');
    }

    public function show($id)
    {
        $admin = UserModel::with('level')->findOrFail($id);
        $activeMenu = 'mahasiswa';
        return view('admin.show', compact('admin', 'activeMenu'));
    }

    public function edit($id)
    {
        $admin = UserModel::findOrFail($id);
        $activeMenu = 'mahasiswa';
        return view('admin.edit', compact('admin', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_user,email,' . $id . ',user_id',
            'level_id' => 'required',
        ]);

        $admin = UserModel::findOrFail($id);
        $admin->update($validated);
        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui');
    }

    public function destroy($id)
    {
        $admin = UserModel::findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus');
    }

}
