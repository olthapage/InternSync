<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Yajra\DataTables\Facades\DataTables;

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
            )
            ->with(['level', 'prodi', 'dosen']);

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
            ->addColumn('dosen', function ($user) {
                return $user->dosen->nama_lengkap ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn  = '<a href="' . url('/mahasiswa/' . $user->mahasiswa_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/mahasiswa/' . $user->mahasiswa_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '
                    <form action="' . url('/mahasiswa/' . $user->mahasiswa_id . '/delete') . '" method="POST" style="display:inline;">
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
        $dosen = DosenModel::all();
        $activeMenu = 'mahasiswa';
        return view('mahasiswa.create', compact('prodi', 'level', 'dosen', 'activeMenu'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_mahasiswa,email',
            'password' => 'required',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'nim' => 'required|unique:m_mahasiswa,nim',
            'status' => 'required|boolean',
            'level_id' => 'required',
            'prodi_id' => 'required',
            'dosen_id' => 'nullable'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        MahasiswaModel::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }
    public function show($id)
    {
        $mahasiswa = MahasiswaModel::with([
            'prodi',
            'level',
            'dosen',
            'preferensiLokasi',
            'skills',
            'kompetensi'
        ])->findOrFail($id);

        $activeMenu = 'mahasiswa';
        return view('mahasiswa.show', compact('mahasiswa', 'activeMenu'));
    }

    public function edit($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        $dosen = DosenModel::all();
        $activeMenu = 'mahasiswa';
        return view('mahasiswa.edit', compact('mahasiswa', 'prodi', 'level', 'dosen', 'activeMenu'));
    }
    public function update(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:m_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'nim' => 'required|unique:m_mahasiswa,nim,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'status' => 'required|boolean',
            'level_id' => 'required',
            'prodi_id' => 'required',
            'dosen_id' => 'nullable'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }
    public function destroy($id)
    {
        MahasiswaModel::findOrFail($id)->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }

}
