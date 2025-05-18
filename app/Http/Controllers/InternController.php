<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InternController extends Controller
{
    /**
     * Tampilkan halaman/form Profil Akademik & Keterampilan
     */
    public function showAcademicProfile()
    {
        $user = Auth::user();
        return view('intern.academic-form', [
            'user' => $user,
        ]);
    }

    /**
     * Simpan data Profil Akademik & Keterampilan via AJAX
     */
    public function storeAcademicProfile(Request $request)
    {
        $rules = [
            'pendidikan'      => 'required|string|min:3',
            'bidang_keahlian' => 'required|string|min:5',
            'sertifikasi'     => 'nullable|string',
            'pengalaman'      => 'nullable|string',
            'ipk'             => 'nullable|numeric|between:0,4',
        ];

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            return response()->json([
                'status'   => false,
                'msgField' => $v->errors(),
                'message'  => 'Validasi gagal.',
            ], 422);
        }

        $user = Auth::user();
        $user->pendidikan      = $request->pendidikan;
        $user->bidang_keahlian = $request->bidang_keahlian;
        $user->sertifikasi     = $request->sertifikasi;
        $user->pengalaman      = $request->pengalaman;
        $user->ipk             = $request->ipk;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profil akademik & keterampilan berhasil disimpan.'
        ]);
    }

    /**
     * Tampilkan halaman/form Preferences
     */
    public function showPreferences()
    {
        $user = Auth::user();
        return view('intern.preferences', [
            'activeMenu' => 'preferences',
        ]);
    }

    /**
     * Simpan data Preferences via AJAX
     */
    public function updatePreferences(Request $request)
    {
        $rules = [
            'region'      => 'required|string',
            'intern_type' => 'required|string',
        ];

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            return response()->json([
                'errors'  => $v->errors(),
                'message' => 'Validasi gagal.'
            ], 422);
        }

        $user = Auth::user();
        $user->region      = $request->region;
        $user->intern_type = $request->intern_type;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Preferensi lokasi & jenis magang berhasil diperbarui.'
        ]);
    }
}
