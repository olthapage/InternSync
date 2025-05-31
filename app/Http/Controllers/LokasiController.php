<?php

namespace App\Http\Controllers;

use App\Models\KotaModel; // Pastikan Anda sudah memiliki model ini
use App\Models\ProvinsiModel; // Jika perlu mengambil data provinsi juga
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    /**
     * Mengambil daftar kota berdasarkan provinsi_id.
     *
     * @param  int  $provinsi_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKotaByProvinsi($provinsi_id) // Laravel akan otomatis inject ID dari URL
    {
        try {
            // Validasi sederhana untuk memastikan provinsi_id adalah angka
            if (!is_numeric($provinsi_id)) {
                return response()->json(['error' => 'ID Provinsi tidak valid.'], 400);
            }

            // Ambil data kota berdasarkan provinsi_id
            // Pilih hanya kolom yang dibutuhkan (kota_id dan kota_nama)
            $kotas = KotaModel::where('provinsi_id', $provinsi_id)
                              ->orderBy('kota_nama', 'asc') // Urutkan berdasarkan nama kota
                              ->select('kota_id', 'kota_nama')
                              ->get();

            if ($kotas->isEmpty()) {
                // Kembalikan array kosong jika tidak ada kota ditemukan, bukan error
                return response()->json([]);
            }

            return response()->json($kotas); // Kembalikan data kota dalam format JSON

        } catch (\Exception $e) {
            Log::error("Error fetching kota by provinsi_id {$provinsi_id}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kota. Terjadi kesalahan pada server.'], 500);
        }
    }

    // Anda bisa menambahkan method lain di sini, misalnya untuk mengambil semua provinsi jika dibutuhkan
    // public function getAllProvinsi()
    // {
    //     try {
    //         $provinsis = ProvinsiModel::orderBy('provinsi_nama', 'asc')
    //                                   ->select('provinsi_id', 'provinsi_nama')
    //                                   ->get();
    //         return response()->json($provinsis);
    //     } catch (\Exception $e) {
    //         Log::error("Error fetching all provinsi: " . $e->getMessage());
    //         return response()->json(['error' => 'Gagal mengambil data provinsi.'], 500);
    //     }
    // }
}
