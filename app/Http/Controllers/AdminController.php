<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::all();
        $activeMenu = 'admin';
        return view('admin.index', compact('admin', 'activeMenu'));
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
