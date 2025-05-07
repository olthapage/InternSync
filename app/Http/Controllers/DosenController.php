<?php

namespace App\Http\Controllers;
use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::with('prodi')->get();
        $activeMenu = 'dosen';
        return view('dosen.index', compact('dosen', 'activeMenu'));
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
                $btn  = '<a href="' . url('/dosen/' . $user->dosen_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/dosen/' . $user->dosen_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '
                    <form action="' . url('/dosen/' . $user->dosen_id . '/delete') . '" method="POST" style="display:inline;">
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
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        $activeMenu = 'dosen';
        return view('dosen.create', compact('prodi', 'level', 'activeMenu'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_dosen,email',
            'password' => 'required|min:6',
            'nip' => 'required|unique:m_dosen,nip',
            'level_id' => 'required',
            'prodi_id' => 'required'
        ]);

        DosenModel::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => $request->password,
            'nip' => $request->nip,
            'level_id' => $request->level_id,
            'prodi_id' => $request->prodi_id
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan');
    }
    public function show($id)
    {
        $dosen = DosenModel::with(['prodi', 'level'])->findOrFail($id);
        $activeMenu = 'dosen';
        return view('dosen.show', compact('dosen', 'activeMenu'));
    }
    public function edit($id)
    {
        $dosen = DosenModel::findOrFail($id);
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        $activeMenu = 'dosen';
        return view('dosen.edit', compact('dosen', 'prodi', 'level', 'activeMenu'));
    }
    public function update(Request $request, $id)
    {
        $dosen = DosenModel::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_dosen,email,' . $id . ',dosen_id',
            'nip' => 'required|unique:m_dosen,nip,' . $id . ',dosen_id',
            'level_id' => 'required',
            'prodi_id' => 'required'
        ]);

        $data = $request->only(['nama_lengkap', 'email', 'nip', 'level_id', 'prodi_id']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $dosen->update($data);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diperbarui');
    }
    public function destroy($id)
    {
        $dosen = DosenModel::findOrFail($id);
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus');
    }
}
