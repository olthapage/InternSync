<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ProdiModel;
use App\Models\DosenModel;
use App\Models\KotaModel;
use App\Models\ProvinsiModel;

class InternController extends Controller
{
    /**
     * Tampilkan halaman/form Profil Akademik & Keterampilan
     */
    public function showAcademicProfile(Request $request)
    {
        $data = [
            'user'      => Auth::user(),
            'prodis'    => ProdiModel::all(),
            'dosens'    => DosenModel::all(),
            'mahasiswa' => Auth::user()->mahasiswa ?? null,
        ];

        // Jika request AJAX, kembalikan partial form saja
        if ($request->ajax()) {
            return view('intern.academic-form', $data);  // langsung return form full
        }

        return view('intern.academic', $data + ['activeMenu' => 'academicProfile']);
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
    public function showPreferences(Request $request)
    {
        $data = [
            'user'       => Auth::user(),
            'province'       => ProvinsiModel::all(),
            'activeMenu' => 'preferences',
        ];

        // Jika AJAX, kembalikan partial preferences (jika tersedia)
        if ($request->ajax()) {
            return view('intern.preferences', $data);
        }

        return view('intern.preferences', $data);
    }

    public function getKotaByProvinsi(Request $request)
    {
        $provinsiId = $request->provinsi_id;

        if (!$provinsiId) {
            return response()->json([], 400);
        }

        $kotas = KotaModel::where('provinsi_id', $provinsiId)
                    ->orderBy('kota_nama')
                    ->get(['kota_id', 'kota_nama']);

        return response()->json($kotas);
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
