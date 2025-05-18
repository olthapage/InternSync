<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;

class ProfileController extends Controller
{
    private function getAuthenticatedUser()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return Auth::guard('mahasiswa')->user();
        } elseif (Auth::guard('dosen')->check()) {
            return Auth::guard('dosen')->user();
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }
        return null;
    }

    public function index()
    {
        $user = $this->getAuthenticatedUser();
        $activeMenu = 'profile';
        return view('profile.index', compact('user', 'activeMenu'));
    }

    public function edit()
    {
        $user = $this->getAuthenticatedUser();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Atur validasi dasar
        $rules = [
            'nama_lengkap' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Validasi berdasarkan jenis user
        if ($user instanceof MahasiswaModel) {
            $rules['email'] = 'required|email|unique:m_mahasiswa,email,' . $user->id;
            $rules['nim'] = 'required|digits:8';
        } elseif ($user instanceof DosenModel) {
            $rules['email'] = 'required|email|unique:m_dosen,email,' . $user->id;
            $rules['nip'] = 'required|digits:10';
        } else {
            $rules['email'] = 'required|email|unique:m_user,email,' . $user->id;
        }

        $request->validate($rules);

        // Update data
        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;

        if ($user instanceof MahasiswaModel) {
            $user->nim = $request->nim;
        } elseif ($user instanceof DosenModel) {
            $user->nip = $request->nip;
        }

        // Update foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
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
