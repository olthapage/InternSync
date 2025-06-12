<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\ValidasiAkun;
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

    public function loginCompany()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login_company');
    }

    public function postlogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    // Coba login ke semua guard
    $guards = ['web', 'mahasiswa', 'dosen', 'industri'];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login berhasil',
                    'redirect' => url('/dashboard')
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah.'
        ]);
    }

    public function companylogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:industri'
        ]);

        $credentials = $request->only('email', 'password');
        $guard = $request->role;

        if (Auth::guard($guard)->attempt($credentials)) {
            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'redirect' => url('/dashboard')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        foreach (['mahasiswa', 'web', 'dosen', 'industri'] as $guard) {
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
        // if (Auth::guard('mahasiswa')->check() || Auth::guard('dosen')->check()) {
        //     return redirect('/dashboard');
        // }

        return view('auth.signup');
    }
    public function postsignup(Request $request)
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255|min:3',
            'password' => 'required|string|min:6|max:20|confirmed',
            'username' => 'required|string|unique:validasi_akun,username',
            'email' => 'required|string|unique:validasi_akun,email',
        ];

        $messages = [
            'nama_lengkap.required' => 'Nama lengkap tidak boleh kosong.',
            'nama_lengkap.min' => 'Nama lengkap minimal 3 karakter.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 20 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'username.required' => 'NIM atau NIDN wajib diisi.',
            'username.unique' => 'NIM/NIDN sudah terdaftar.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email sudah terdaftar.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Terdapat kesalahan pada input Anda.',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        // Cek format NIM/NIDN hanya untuk info atau validasi tambahan
        $username = $request->input('username');
        $role_guess = null;
        if (preg_match('/^\d{10}$/', $username)) {
            $role_guess = 'mahasiswa';
        } elseif (preg_match('/^\d{8,9}$/', $username)) {
            $role_guess = 'dosen';
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Format NIM (10 digit) atau NIDN (8-9 digit) tidak valid.'
            ], 422);
        }


        // Simpan ke tabel validasi akun
        $akun = ValidasiAkun::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status_validasi' => 'pending', // Default, menunggu validasi admin
            'perkiraan_role' => $role_guess, // opsional untuk membantu admin
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pendaftaran berhasil. Menunggu validasi admin.',
            'redirect' => route('pendaftaran.berhasil') // arahkan ke halaman sukses
        ]);
    }
}
