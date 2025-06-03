<?php
namespace App\Http\Controllers\mahasiswa;

use App\Models\DosenModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    public function store(Request $request)
    {
        // Ambil data mahasiswa yang sedang login
        $mahasiswa = auth()->user();
        $nim       = $mahasiswa->nim;

        // Validasi input dengan nama tabel yang benar
        $validated = $request->validate([
            'prodi_id'              => 'required|exists:tabel_prodi,prodi_id',  // Sesuaikan dengan nama tabel yang benar
            'dpa_id'              => 'nullable|exists:m_dosen,dosen_id',  // Sesuaikan dengan nama tabel yang benar
            'ipk'                   => 'nullable|numeric|min:0|max:4',
            'organisasi'            => 'required|string|in:tidak_ikut,aktif,sangat_aktif',
            'lomba'                 => 'required|string|in:tidak_ikut,aktif,sangat_aktif',
            'sertifikat_kompetensi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sertifikat_organisasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sertifikat_lomba'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pakta_integritas'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'daftar_riwayat_hidup'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'khs'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktm'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_izin_ortu'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bpjs'                  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sktm_kip'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proposal'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Menyimpan data non-file (prodi, dosen, dan ipk)
        $dataToUpdate = [
            'prodi_id' => $request->prodi_id,
            'dpa_id' => $request->dpa_id,
            'ipk'      => $request->ipk,
        ];

        // Definisi field file yang akan diupload
        $fileFields = [
            'sertifikat_kompetensi',
            'sertifikat_organisasi',
            'sertifikat_lomba',
            'pakta_integritas',
            'daftar_riwayat_hidup',
            'khs',
            'ktp',
            'ktm',
            'surat_izin_ortu',
            'bpjs',
            'sktm_kip',
            'proposal',
        ];

        // Proses upload untuk setiap file
        foreach ($fileFields as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                // Hapus file lama jika ada dan file baru diunggah
                if ($mahasiswa->$field && Storage::disk('public')->exists($mahasiswa->$field)) {
                    Storage::disk('public')->delete($mahasiswa->$field);
                }

                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();
                // Path penyimpanan: verifikasi/{NIM}/nama_field.extensi
                $path = $file->storeAs(
                    "verifikasi/{$nim}",
                    "{$field}_" . time() . ".{$extension}", // Tambahkan timestamp untuk keunikan jika diupload ulang
                    'public'
                );
                $dataToUpdate[$field] = $path;
            }
        }


        $dataToUpdate['status_verifikasi'] = 'pending';

        MahasiswaModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->update($dataToUpdate);

        return back()->with('success', 'Data berhasil disimpan.');
    }

    public function getDosenByProdi(Request $request, $prodi_id)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $dosens = DosenModel::where('prodi_id', $prodi_id)
                            ->where('role_dosen', 'dpa') // Hanya dosen dengan peran DPA
                            ->orderBy('nama_lengkap', 'asc')
                            ->get(['dosen_id', 'nama_lengkap']); // Hanya ambil kolom yang dibutuhkan

        return response()->json($dosens);
    }
}
