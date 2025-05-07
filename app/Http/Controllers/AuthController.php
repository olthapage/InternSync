<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:web,mahasiswa,dosen'
        ]);

        $credentials = $request->only('email', 'password');
        $guard = $request->role;

        if (Auth::guard($guard)->attempt($credentials)) {
            return response()->json([
                'status' => true,
                'message' => 'Login berhasil sebagai ' . ucfirst($guard),
                'redirect' => url('/')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        foreach (['mahasiswa', 'web', 'dosen'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }


    public function signup()
    {
        if (Auth::guard('mahasiswa')->check() || Auth::guard('dosen')->check()) {
            return redirect('/');
        }

        return view('auth.signup');
    }

    public function postsignup(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:m_user,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:mahasiswa,dosen',
        ]);

        // Validasi NIDN untuk Dosen dan NIM untuk Mahasiswa
        if ($request->role === 'dosen') {
            $validator->after(function ($validator) use ($request) {
                if (strlen($request->nidn) !== 10 || !preg_match('/^\d+$/', $request->nidn)) {
                    $validator->errors()->add('nidn', 'NIDN tidak valid.');
                }
            });
        } elseif ($request->role === 'mahasiswa') {
            $validator->after(function ($validator) use ($request) {
                if (strlen($request->nim) !== 8 || !preg_match('/^\d+$/', $request->nim)) {
                    $validator->errors()->add('nim', 'NIM tidak valid.');
                }
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        // Menyimpan data berdasarkan role
        if ($request->role === 'mahasiswa') {
            $user = MahasiswaModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nim' => $request->nim,
                'level_id' => 2,
            ]);

            Auth::guard('mahasiswa')->login($user);
        } elseif ($request->role === 'dosen') {
            $user = DosenModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nip' => $request->nidn,
                'level_id' => 3,
            ]);

            Auth::guard('dosen')->login($user);
        }

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil.',
            'redirect' => url('/')
        ]);
    }
}
