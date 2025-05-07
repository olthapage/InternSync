<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeMenu = 'profile';
        return view('profile.index', compact('user', 'activeMenu'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:m_user,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($user->role === 'mahasiswa') {
            $rules['nim'] = 'required|digits:8';
        } elseif ($user->role === 'dosen') {
            $rules['nidn'] = 'required|digits:10';
        }

        $request->validate($rules);

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;

        if ($user->role === 'mahasiswa') {
            $user->nim = $request->nim;
        } elseif ($user->role === 'dosen') {
            $user->nidn = $request->nidn;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/foto/' . $user->foto);
            }

            $foto = $request->file('foto');
            $filename = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/foto', $filename);
            $user->foto = $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'foto' => $user->foto ? asset('storage/foto/' . $user->foto) : null,
        ]);
    }
}
