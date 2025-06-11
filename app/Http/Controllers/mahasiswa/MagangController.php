<?php

namespace App\Http\Controllers\mahasiswa;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Models\PengajuanModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MagangController extends Controller
{
     public function index()
    {
        $mahasiswaId = Auth::id();
        $activeMenu = 'magang';

        // Eager load relasi yang dibutuhkan untuk view
        $magangItems = MagangModel::with([
            'lowongan.industri', // Untuk nama industri
            'lowongan.kategoriSkill', // Untuk nama kategori skill
            'lowongan.lokasiProvinsi', // Untuk alamat lengkap display
            'lowongan.lokasiKota.provinsi' // Untuk alamat lengkap display (kota dan provinsinya)
        ])
        ->where('mahasiswa_id', $mahasiswaId)
        ->orderBy('created_at', 'desc')
        ->get();

        if ($magangItems->isNotEmpty()) {
            // Kumpulkan lowongan_id dari item magang yang dimiliki mahasiswa
            $lowonganIds = $magangItems->pluck('lowongan_id')->unique()->toArray();

            // Ambil data pengajuan yang relevan dalam satu query
            // Diasumsikan bahwa sebuah entri di mahasiswa_magang berarti pengajuannya sudah 'diterima'
            $pengajuanData = PengajuanModel::where('mahasiswa_id', $mahasiswaId)
                                           ->whereIn('lowongan_id', $lowonganIds)
                                           // Anda bisa menambahkan ->where('status', 'diterima') jika perlu
                                           // untuk memastikan hanya mengambil pengajuan yang statusnya diterima.
                                           // Namun, jika MagangModel dibuat hanya setelah pengajuan diterima,
                                           // maka ini mungkin tidak perlu.
                                           ->get()
                                           // Buat composite key untuk mapping mudah: "mahasiswaid_lowonganid"
                                           ->keyBy(function ($item) {
                                               return $item->mahasiswa_id . '_' . $item->lowongan_id;
                                           });

            // Lampirkan data pengajuan yang sesuai ke setiap item magang
            $magangItems->each(function ($magangItem) use ($pengajuanData) {
                $key = $magangItem->mahasiswa_id . '_' . $magangItem->lowongan_id;
                // Menggunakan setRelation agar bisa diakses seperti relasi biasa di Blade ($item->pengajuan)
                $magangItem->setRelation('pengajuan', $pengajuanData->get($key));
            });
        }

        return view('mahasiswa_page.magang.index', [
            'activeMenu' => $activeMenu,
            'magang' => $magangItems // Kirim $magangItems sebagai $magang
        ]);
    }
   public function storeEvaluasi(Request $request, $magang_id)
    {
        $mahasiswaId = Auth::id();

        $validator = Validator::make($request->all(), [
            'evaluasi' => 'required|string|min:20',
        ], [
            'evaluasi.required' => 'Kolom evaluasi tidak boleh kosong.',
            'evaluasi.min' => 'Evaluasi minimal harus :min karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mahasiswa.magang.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        $magangItem = MagangModel::where('mahasiswa_magang_id', $magang_id)
                                 ->where('mahasiswa_id', $mahasiswaId)
                                 ->first();

        if (!$magangItem) {
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Data magang tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($magangItem->status !== 'selesai') {
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Evaluasi hanya bisa diisi untuk magang yang telah selesai.');
        }

        if ($magangItem->evaluasi) {
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Anda sudah pernah mengirimkan evaluasi untuk magang ini.');
        }

        $magangItem->evaluasi = $request->input('evaluasi');
        $magangItem->save();

        return redirect()->route('mahasiswa.magang.index')->with('success', 'Evaluasi berhasil dikirim. Terima kasih atas partisipasi Anda.');
    }

    public function generateSuratKeterangan($magang_id)
    {
        $mahasiswaId = Auth::id();

        // 1. Ambil data magang dengan relasi yang dibutuhkan
        $magang = MagangModel::with([
            'mahasiswa.prodi',
            'mahasiswa.dosen', // Dosen Pembimbing
            'lowongan.industri',
        ])
        ->where('mahasiswa_magang_id', $magang_id)
        ->where('mahasiswa_id', $mahasiswaId)
        ->first();

        // 2. Validasi Keamanan dan Status
        if (!$magang) {
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Data magang tidak ditemukan.');
        }

        if ($magang->status !== 'selesai') {
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Surat keterangan hanya bisa diunduh untuk magang yang telah selesai.');
        }

        // 3. Ambil periode magang dari tabel pengajuan (seperti di method index)
        $pengajuan = PengajuanModel::where('mahasiswa_id', $magang->mahasiswa_id)
                                  ->where('lowongan_id', $magang->lowongan_id)
                                  ->where('status', 'diterima') // Pastikan ambil pengajuan yang diterima
                                  ->first();

        if (!$pengajuan) {
            // Fallback jika data pengajuan tidak ditemukan, meskipun seharusnya ada
            return redirect()->route('mahasiswa.magang.index')->with('error', 'Detail periode magang tidak ditemukan.');
        }

        // 4. Siapkan semua data untuk di-pass ke view PDF
        $data = [
            'nomor_surat'       => 'SKM/' . date('Y') . '/' . $magang->mahasiswa_magang_id,
            'tanggal_terbit'    => Carbon::now()->isoFormat('D MMMM YYYY'),
            'mahasiswa'         => $magang->mahasiswa,
            'industri'          => $magang->lowongan->industri,
            'lowongan'          => $magang->lowongan,
            'pengajuan'         => $pengajuan,
            'dosen_pembimbing'  => $magang->mahasiswa->dosen,
        ];

        // 5. Generate PDF
        $pdf = PDF::loadView('sertifikat.surat_keterangan_template', $data);

        // Atur nama file yang akan di-download
        $fileName = 'Surat_Keterangan_Magang_' . str_replace(' ', '_', $magang->mahasiswa->nama_lengkap) . '.pdf';

        // Tampilkan PDF di browser atau langsung download
        return $pdf->stream($fileName);
    }
}
