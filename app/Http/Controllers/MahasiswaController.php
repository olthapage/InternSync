<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::with('prodi')->get();
        $activeMenu = 'mahasiswa';
        return view('mahasiswa.index', compact('mahasiswa', 'activeMenu'));
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
