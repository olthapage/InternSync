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
            'role' => 'required|in:web,mahasiswa,dosen,industri'
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
        $role = $request->input('role');

        $rules = [
            'nama_lengkap' => 'required|string|max:255|min:3',
            'password' => 'required|string|min:6|max:20|confirmed',
            'role' => 'required|in:mahasiswa,dosen',
            'terms' => 'required',
        ];

        $messages = [
            'nama_lengkap.required' => 'Nama lengkap tidak boleh kosong.',
            'nama_lengkap.min' => 'Nama lengkap minimal 3 karakter.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 20 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Silakan pilih role Anda.',
            'terms.required' => 'Anda harus menyetujui Syarat dan Ketentuan.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
        ];

        if ($role === 'dosen') {
            $rules['nidn'] = 'required|digits:10|unique:m_dosen,nip';
            $rules['email'] = 'required|email|unique:m_dosen,email';
            $rules['role_dosen_signup'] = 'required|in:dpa,pembimbing';

            $messages['nidn.required'] = 'NIDN wajib diisi untuk dosen.';
            $messages['nidn.digits'] = 'NIDN harus terdiri dari 10 digit.';
            $messages['nidn.unique'] = 'NIDN sudah terdaftar.';
            $messages['email.unique'] = 'Email sudah terdaftar untuk dosen.';

            $messages['role_dosen_signup.required'] = 'Peran dosen (DPA/Pembimbing) wajib dipilih.';
            $messages['role_dosen_signup.in'] = 'Peran dosen yang dipilih tidak valid.';

        } elseif ($role === 'mahasiswa') {
            // GANTI 'm_mahasiswa' dengan nama tabel mahasiswa Anda jika berbeda
            $rules['nim'] = 'required|string|min:8|max:10|digits_between:8,10|unique:m_mahasiswa,nim';
            $rules['email'] = 'required|email|unique:m_mahasiswa,email';
            $messages['nim.required'] = 'NIM wajib diisi untuk mahasiswa.';
            $messages['nim.digits_between'] = 'NIM harus terdiri dari 8-10 digit angka.';
            $messages['nim.min'] = 'NIM minimal 8 digit.';
            $messages['nim.max'] = 'NIM maksimal 10 digit.';
            $messages['nim.unique'] = 'NIM sudah terdaftar.';
            $messages['email.unique'] = 'Email sudah terdaftar untuk mahasiswa.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Terdapat kesalahan pada input Anda.',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $user = null;
        if ($role === 'mahasiswa') {
            $user = MahasiswaModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nim' => $request->nim,
                'level_id' => 2,
            ]);
        } elseif ($role === 'dosen') {
            $user = DosenModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nip' => $request->nidn,
                'level_id' => 3,
                'role_dosen' => $request->role_dosen_signup,
            ]);
        }

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil. Silakan login.',
                'redirect' => route('login')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat pengguna. Silakan coba lagi.'
            ], 500);
        }
    }
}
