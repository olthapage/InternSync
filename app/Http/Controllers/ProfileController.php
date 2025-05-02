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
        $user = UserModel::find(auth()->id());
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = UserModel::find(auth()->id());
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'email' => 'required|email',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = UserModel::find(auth()->id()); 
        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/foto', $filename); 
            $user->foto = $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'foto' => asset('storage/foto/' . $user->foto),
        ]);

    }
}
