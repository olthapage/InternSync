<?php
namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LogHarianDetailModel;
use App\Models\LogHarianModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LogHarianController extends Controller
{
    public function index()
    {
        return view('mahasiswa_page.logharian.index', [
            'activeMenu' => 'logharian',
        ]);
    }

    public function list(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $mahasiswaId       = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');
        $mahasiswaMagangId = MagangModel::where('mahasiswa_id', $mahasiswaId)->value('mahasiswa_magang_id');

        $query = LogHarianModel::where('mahasiswa_magang_id', $mahasiswaMagangId)->with('detail');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        return DataTables::of($query)
            ->addColumn('isi', function ($row) {
                // gabungkan isi dari detail
                return $row->detail->pluck('isi')->implode('<br>');
            })
            ->addColumn('lokasi_kegiatan', function ($row) {
                // misal ambil lokasi dari detail pertama, atau buat join khusus sesuai kebutuhan
                return $row->detail->first()->lokasi ?? '-';
            })
            ->addColumn('status_approval_dosen', function ($row) {
                $status = $row->detail->first()->status_approval_dosen ?? 'pending';
                if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning">Pending</span>';
            })

            ->addColumn('status_approval_industri', function ($row) {
                $status = $row->detail->first()->status_approval_industri ?? 'pending';
                 if ($status == 'disetujui') {
                    return '<span class="badge bg-success">Disetujui</span>';
                } elseif ($status == 'ditolak') {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                return '<span class="badge bg-warning">Pending</span>';
            })
            ->addColumn('aksi', function ($row) {
                $editUrl   = route('logHarian.edit', ['id' => $row->logHarian_id]);
                $detailUrl = route('logHarian.show', ['id' => $row->logHarian_id]);

                return '
                    <button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">Detail</button>
                    <button class="btn btn-sm btn-warning" onclick="modalAction(\'' . $editUrl . '\')">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteLog(' . $row->logHarian_id . ')">Hapus</button>';
            })
            ->editColumn('tanggal', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal));
            })
            ->rawColumns(['isi', 'aksi', 'status_approval_dosen', 'status_approval_industri'])
            ->make(true);
    }

    public function create()
    {
        $user                = Auth::guard('mahasiswa')->user();
        $mahasiswa           = MahasiswaModel::where('nim', $user->nim)->first();
        $defaultLokasiMagang = 'Alamat lowongan tidak ditemukan'; // Fallback default

        if ($mahasiswa) {
            $magang = MagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->with([
                    'lowongan.lokasiKota.provinsi',    // Untuk DetailLowonganModel->getAlamatLengkapDisplayAttribute()
                    'lowongan.industri.kota.provinsi', // Untuk DetailLowonganModel->getAlamatLengkapDisplayAttribute()
                ])
                ->first();

            if ($magang && $magang->lowongan) {
                // Menggunakan accessor getAlamatLengkapDisplayAttribute dari DetailLowonganModel
                $defaultLokasiMagang = $magang->lowongan->alamat_lengkap_display;
            } else if ($magang && $magang->lowongan && ! $magang->lowongan->use_specific_location && $magang->lowongan->industri) {
                                                                                  // Jika tidak pakai lokasi spesifik, coba ambil dari alamat industri
                $defaultLokasiMagang = $magang->lowongan->alamat_lengkap_display; // Accessor sudah menghandle ini
            }
        }

        return view('mahasiswa_page.logharian.create', [
            'activeMenu'          => 'logharian',
            'defaultLokasiMagang' => $defaultLokasiMagang,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $mahasiswaId       = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');
        $mahasiswaMagangId = MagangModel::where('mahasiswa_id', $mahasiswaId)->value('mahasiswa_magang_id');

        $request->validate([
            'tanggal'               => 'required|date',
            'aktivitas'             => 'required|array|min:1',
            'aktivitas.*.deskripsi' => 'required|string',
            'aktivitas.*.lokasi'    => 'required|string',
            'aktivitas.*.tanggal'   => 'required|date',
        ]);

        // Simpan log harian header (tanggal umum)
        $log = LogHarianModel::create([
            'mahasiswa_magang_id' => $mahasiswaMagangId,
            'tanggal'             => $request->tanggal,
        ]);

        // Simpan detail aktivitas dengan tanggal masing-masing
        foreach ($request->aktivitas as $aktivitas) {
            LogHarianDetailModel::create([
                'logHarian_id'             => $log->logHarian_id,
                'isi'                      => $aktivitas['deskripsi'],
                'lokasi'                   => $aktivitas['lokasi'],
                'tanggal_kegiatan'         => $aktivitas['tanggal'],
                'status_approval_dosen'    => 'pending',
                'status_approval_industri' => 'pending',
                'catatan_dosen'            => null,
                'catatan_industri'         => null,
            ]);
        }

        return response()->json(['success' => 'Log harian berhasil disimpan.']);
    }

    public function edit($id)
    {
        $log = LogHarianModel::with('detail')->findOrFail($id);

        // Ambil default lokasi magang untuk konsistensi jika ada aktivitas baru atau untuk referensi
        $user                = Auth::guard('mahasiswa')->user();
        $mahasiswa           = MahasiswaModel::where('nim', $user->nim)->first();
        $defaultLokasiMagang = 'Alamat lowongan tidak ditemukan';

        if ($mahasiswa) {
            $magang = MagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->with([
                    'lowongan.lokasiKota.provinsi',
                    'lowongan.industri.kota.provinsi',
                ])
                ->first();

            if ($magang && $magang->lowongan) {
                $defaultLokasiMagang = $magang->lowongan->alamat_lengkap_display;
            }
        }

        return view('mahasiswa_page.logharian.edit', [
            'log'                 => $log,
            'activeMenu'          => 'logharian',
            'defaultLokasiMagang' => $defaultLokasiMagang, // Kirim ke view edit
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'                      => 'required|date',
            'aktivitas'                    => 'required|array|min:1',
            'aktivitas.*.deskripsi'        => 'required|string',
            'aktivitas.*.lokasi'           => 'required|string',
            'aktivitas.*.tanggal_kegiatan' => 'required|date',
        ]);

        $log = LogHarianModel::findOrFail($id);
        $log->update([
            'tanggal' => $request->tanggal,
        ]);

        LogHarianDetailModel::where('logHarian_id', $log->logHarian_id)->delete();

        foreach ($request->aktivitas as $aktivitas) {
            LogHarianDetailModel::create([
                'logHarian_id'             => $log->logHarian_id,
                'isi'                      => $aktivitas['deskripsi'],
                'lokasi'                   => $aktivitas['lokasi'],
                'tanggal_kegiatan'         => $aktivitas['tanggal_kegiatan'],
                'status_approval_dosen'    => 'pending',
                'status_approval_industri' => 'pending',
                'catatan_dosen'            => null,
                'catatan_industri'         => null,
            ]);
        }

        return response()->json(['success' => 'Log harian berhasil diperbarui.']);
    }
    public function show(Request $request, $id)
    {
        $logharian = LogHarianModel::with(['mahasiswaMagang.mahasiswa', 'detail'])
            ->findOrFail($id);

        if ($request->ajax()) {
            return view('mahasiswa_page.logharian.show', compact('logharian'));
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('logHarian.index')->with('error', 'Aksi tidak diizinkan.');
        }

        $user = Auth::guard('mahasiswa')->user();
        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized. Silakan login kembali.',
            ], 401);
        }

        // ... (kode untuk get mahasiswa dan magang tetap sama) ...
        $mahasiswa = MahasiswaModel::where('nim', $user->nim)->first();
        if (! $mahasiswa) {
            return response()->json(['status' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }
        $magang = MagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)->first();
        if (! $magang) {
            return response()->json(['status' => false, 'message' => 'Anda belum terdaftar pada program magang.'], 403);
        }

                                              // Eager load relasi 'detail' untuk pengecekan
        $log = LogHarianModel::with('detail') // Muat relasi detail
            ->where('logHarian_id', $id)
            ->where('mahasiswa_magang_id', $magang->mahasiswa_magang_id)
            ->first();

        if (! $log) {
            return response()->json([
                'status'  => false,
                'message' => 'Log harian tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.',
            ], 404);
        }

        // Cek apakah ada 'detail' dari log ini yang sudah disetujui oleh dosen ATAU industri.
        $isApproved = $log->detail->contains(function ($detail) {
            return $detail->status_approval_dosen == 'disetujui' || $detail->status_approval_industri == 'disetujui';
        });

        if ($isApproved) {
            // Jika sudah ada yang disetujui, gagalkan penghapusan.
            return response()->json([
                'status'  => false,
                'message' => 'Gagal! Log harian tidak dapat dihapus karena sudah divalidasi (disetujui).',
            ], 403); // 403 Forbidden - Aksi tidak diizinkan
        }

        try {
            // Hapus relasi detail terlebih dahulu
            $log->detail()->delete();
            // Kemudian hapus log utama
            $log->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Log harian berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            // Log error jika perlu: Log::error('Gagal menghapus log harian: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menghapus log harian. Silakan coba lagi.',
            ], 500);
        }
    }

    public function export_pdf()
    {
        $user = Auth::guard('mahasiswa')->user(); // Menggunakan Auth::guard('mahasiswa')
        if (! $user) {
            // Handle jika tidak ada user mahasiswa yang login
            // Redirect atau tampilkan pesan error, contoh:
            return redirect()->route('mahasiswa.login')->with('error', 'Anda harus login sebagai mahasiswa untuk mengekspor PDF.');
        }

        $mahasiswa = MahasiswaModel::where('nim', $user->nim)->first();
        if (! $mahasiswa) {
            // Ini seharusnya tidak terjadi jika user sudah login dengan guard mahasiswa
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data dosen pembimbing mahasiswa (jika ada dan diperlukan)
        $dosen = null;
        if ($mahasiswa->dosen_id) { // Asumsi ada field dosen_id di tabel mahasiswa
            $dosen = DosenModel::find($mahasiswa->dosen_id);
        }

        // Ambil data magang mahasiswa beserta informasi lowongan untuk alamat
        $magang = MagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->with([
                'lowongan.lokasiKota.provinsi',    // Untuk DetailLowonganModel->getAlamatLengkapDisplayAttribute()
                'lowongan.industri.kota.provinsi', // Untuk DetailLowonganModel->getAlamatLengkapDisplayAttribute()
            ])
            ->first();

        $lokasiMagangDisplay = 'Alamat magang tidak ditentukan'; // Fallback
        $mahasiswaMagangId   = null;

        if ($magang) {
            $mahasiswaMagangId = $magang->mahasiswa_magang_id;
            if ($magang->lowongan) {
                // Menggunakan accessor getAlamatLengkapDisplayAttribute dari DetailLowonganModel
                $lokasiMagangDisplay = $magang->lowongan->alamat_lengkap_display;
            } else {
                $lokasiMagangDisplay = 'Informasi lowongan tidak ditemukan.';
            }
        } else {
            // Jika mahasiswa tidak memiliki data magang aktif
            return redirect()->back()->with('error', 'Anda belum terdaftar pada program magang aktif.');
        }

        // Jika $mahasiswaMagangId masih null setelah pengecekan magang
        if (is_null($mahasiswaMagangId)) {
            return redirect()->back()->with('error', 'Data magang mahasiswa tidak valid untuk ekspor.');
        }

        $logharian = LogHarianModel::where('mahasiswa_magang_id', $mahasiswaMagangId)
            ->with('detail') // Eager load detail untuk efisiensi di view PDF
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($logharian->isEmpty()) {
            // Jika tidak ada log harian untuk diekspor
            return redirect()->back()->with('info', 'Tidak ada data log harian untuk diekspor.');
        }

        $pdf = Pdf::loadView('mahasiswa_page.logharian.export_pdf', [
            'mahasiswa'    => $mahasiswa,
            'dosen'        => $dosen,               // Bisa null jika tidak ada dosen_id
            'lokasiMagang' => $lokasiMagangDisplay, // Menggunakan alamat dari lowongan
            'logharian'    => $logharian,
        ]);

        $pdf->setPaper('a4', 'portrait');
        // setOption "isRemoteEnabled" biasanya untuk DomPDF versi lama atau jika ada masalah dengan path gambar/CSS eksternal
        // Untuk versi lebih baru, mungkin tidak selalu diperlukan atau defaultnya sudah true.
        $pdf->setOption("isRemoteEnabled", true);

        return $pdf->stream('Log Harian - ' . $mahasiswa->nama . ' - ' . now()->format('Ymd_His') . '.pdf');
    }
}
