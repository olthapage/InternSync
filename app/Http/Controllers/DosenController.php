<?php

namespace App\Http\Controllers;
use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::with('prodi')->get();
        return view('dosen.index', compact('dosen'));
    }
    public function create()
    {
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        return view('dosen.create', compact('prodi', 'level'));
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
        return view('dosen.show', compact('dosen'));
    }
    public function edit($id)
    {
        $dosen = DosenModel::findOrFail($id);
        $prodi = ProdiModel::all();
        $level = LevelModel::all();
        return view('dosen.edit', compact('dosen', 'prodi', 'level'));
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
