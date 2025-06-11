<?php
namespace App\Http\Controllers\industri;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\IndustriModel;
use App\Models\LogHarianDetailModel;
use App\Models\LogHarianModel;
use App\Models\MagangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ManajemenMagangController extends Controller
{
    public function index()
    {
        $activeMenu   = 'manajemen';
        $userIndustri = Auth::user(); // Asumsi user yang login adalah instance model yang memiliki industri_id atau adalah IndustriModel itu sendiri

                                                                                 // Pastikan kita mendapatkan industri_id
        $industriId = $userIndustri->industri_id ?? ($userIndustri->id ?? null); // Sesuaikan ini jika user Auth bukan IndustriModel langsung

        if (! $industriId) {
            return redirect()->back()->with('error', 'Tidak dapat mengidentifikasi industri Anda.');
        }

        // Ambil daftar lowongan milik industri ini untuk filter
        $listLowonganIndustri = DetailLowonganModel::where('industri_id', $industriId)
            ->orderBy('judul_lowongan', 'asc')
            ->get();

        // Ambil status unik dari tabel magang milik industri ini untuk filter status
        // Ini lebih dinamis daripada hardcode
        $listStatusMagang = MagangModel::whereHas('lowongan', function ($q) use ($industriId) {
            $q->where('industri_id', $industriId);
        })->distinct()->pluck('status')->filter()->sort()->values();

        return view('industri_page.magang.index', compact(
            'activeMenu',
            'userIndustri', // Kirim user industri jika namanya mau ditampilkan
            'listLowonganIndustri',
            'listStatusMagang'
        ));
    }

    public function list(Request $request)
    {
        $userIndustri = Auth::user();
        $industriId   = $userIndustri->industri_id ?? ($userIndustri->id ?? null);

        if (! $industriId) {
            // Mengembalikan respons kosong atau error untuk DataTables jika industri tidak teridentifikasi
            return DataTables::of(collect([]))->make(true);
        }

        $query = MagangModel::with([
            'mahasiswa' => function ($query) {
                $query->select('mahasiswa_id', 'nama_lengkap', 'nim', 'foto'); // Pilih kolom yang dibutuhkan
            },
            'lowongan'  => function ($query) {
                $query->select('lowongan_id', 'judul_lowongan', 'tanggal_mulai', 'tanggal_selesai', 'industri_id'); // Pilih kolom
            },
        ])
            ->whereHas('lowongan', function ($q) use ($industriId) {
                $q->where('industri_id', $industriId);
            })
            ->select('mahasiswa_magang.*'); // Mulai dengan memilih semua kolom dari tabel utama

        // Filter berdasarkan lowongan_id
        if ($request->filled('filter_lowongan_id')) {
            $query->where('lowongan_id', $request->filter_lowongan_id);
        }

        // Filter berdasarkan status magang (dari field 'status' di MagangModel)
        if ($request->filled('filter_status_magang')) {
            $query->where('status', $request->filter_status_magang);
        }

        // Pencarian global
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($sq) use ($search) {
                    $sq->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                    ->orWhereHas('lowongan', function ($sq) use ($search) {
                        $sq->where('judul_lowongan', 'like', "%{$search}%");
                    });
            });
        }

        return DataTables::of($query)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex sebagai nomor urut
            ->addColumn('mahasiswa_detail', function ($row) {
                $nama = $row->mahasiswa->nama_lengkap ?? 'N/A';
                $nim  = $row->mahasiswa->nim ?? '-';
                $foto = $row->mahasiswa->foto ? asset('storage/foto/' . $row->mahasiswa->foto) : asset('assets/default-profile.png'); // Sesuaikan path default avatar

                return '
                <div class="d-flex align-items-center">
                    <img src="' . $foto . '" class="avatar avatar-sm me-3 rounded-circle" alt="foto_mahasiswa">
                    <div>
                        <h6 class="mb-0 text-sm">' . $nama . '</h6>
                        <p class="text-xs text-secondary mb-0">' . $nim . '</p>
                    </div>
                </div>';
            })
            ->addColumn('lowongan_judul', function ($row) {
                return $row->lowongan->judul_lowongan ?? 'N/A';
            })
            ->addColumn('periode_magang', function ($row) {
                if (isset($row->lowongan->tanggal_mulai) && isset($row->lowongan->tanggal_selesai)) {
                    $mulai   = Carbon::parse($row->lowongan->tanggal_mulai)->isoFormat('D MMM YY');
                    $selesai = Carbon::parse($row->lowongan->tanggal_selesai)->isoFormat('D MMM YY');
                    return $mulai . ' - ' . $selesai;
                }
                return 'N/A';
            })
            ->addColumn('status_magang', function ($row) {
                $status      = $row->status ?? 'Belum Ada Status'; // Ambil status dari MagangModel
                $statusLower = strtolower($status);

                $badgeBgClass = 'bg-secondary'; // Default background class untuk Bootstrap 5
                $textClass    = '';             // Kelas untuk warna teks, defaultnya kontras dengan background (biasanya putih untuk bg gelap)

                // Sesuaikan logika warna badge berdasarkan kemungkinan nilai status Anda
                if (in_array($statusLower, ['aktif', 'berjalan', 'diterima', 'sedang'])) {
                    $badgeBgClass = 'bg-gradient-primary';
                } elseif (in_array($statusLower, ['selesai', 'lulus'])) {
                    $badgeBgClass = 'bg-gradient-success';
                } elseif ($statusLower == 'belum') {
                    $badgeBgClass = 'bg-gradient-info';
                    // Untuk bg-info bawaan BS5, teks default (putih) biasanya sudah kontras.
                    // Jika tema Anda membuat bg-info terang, tambahkan: $textClass = 'text-dark';
                }
                return '<span class="badge ' . $badgeBgClass . ' ' . $textClass . '">' . ucfirst(htmlspecialchars($status)) . '</span>';
            })
            ->addColumn('aksi', function ($row) {
                // $row adalah instance dari MagangModel
                $actionUrl = route('industri.magang.action', $row->mahasiswa_magang_id);

                $buttons = '<div class="btn-group" role="group">';
                // Tombol ini akan mengarah ke halaman action baru
                $buttons .= '<a href="' . $actionUrl . '" class="btn btn-xs btn-outline-primary" title="Kelola Magang"><i class="fas fa-tasks"></i> Kelola</a>';
                // Anda bisa tambahkan tombol lain jika perlu, misal lihat profil mahasiswa
                // $buttons .= '<a href="#" class="btn btn-xs btn-outline-info ms-1" title="Lihat Profil Mahasiswa"><i class="fas fa-user"></i></a>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['mahasiswa_detail', 'status_magang', 'aksi'])
            ->make(true);
    }
    public function action($mahasiswa_magang_id)
    {
        $activeMenu   = 'manajemen';
        $userIndustri = Auth::user();
        $industriId   = null; // Sesuaikan cara Anda mendapatkan industriId dari userIndustri
        if (property_exists($userIndustri, 'industri_id')) {
            $industriId = $userIndustri->industri_id;
        } elseif (method_exists($userIndustri, 'getKey')) { // Jika userIndustri adalah model Industri itu sendiri
            $industriId = $userIndustri->getKey();
        }

        $magang = MagangModel::with([
            'mahasiswa.prodi',
            'lowongan.industri',
            'mahasiswa.pengajuan' => function ($query) {
                $query->where('status', 'diterima')->orderBy('created_at', 'desc');
            },
        ])
            ->where('mahasiswa_magang_id', $mahasiswa_magang_id)
            ->whereHas('lowongan', function ($q) use ($industriId) {
                $q->where('industri_id', $industriId);
            })
            ->firstOrFail();

        $pengajuanDiterima = $magang->mahasiswa->pengajuan()
            ->where('lowongan_id', $magang->lowongan_id)
            ->where('status', 'diterima')
            ->first();

        if (! $pengajuanDiterima) {
            $pengajuanDiterima = PengajuanModel::where('mahasiswa_id', $magang->mahasiswa_id)
                ->where('lowongan_id', $magang->lowongan_id)
                ->where('status', 'diterima')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        $progres              = 0;
        $tanggalMulaiMagang   = null;
        $tanggalSelesaiMagang = null;
        $pesanProgress        = "Tanggal mulai/selesai magang belum ditentukan dari pengajuan.";

        // Ambil tanggal mulai dan selesai dari PengajuanModel yang diterima
        if ($pengajuanDiterima && $pengajuanDiterima->tanggal_mulai && $pengajuanDiterima->tanggal_selesai) {
            $tanggalMulaiMagang   = Carbon::parse($pengajuanDiterima->tanggal_mulai);
            $tanggalSelesaiMagang = Carbon::parse($pengajuanDiterima->tanggal_selesai);
            $hariIni              = Carbon::now();

            if ($tanggalMulaiMagang->gt($tanggalSelesaiMagang)) {
                $pesanProgress = "Tanggal mulai tidak boleh melewati tanggal selesai.";
            } elseif ($hariIni->lt($tanggalMulaiMagang) && $magang->status == 'belum') {
                $progres       = 0;
                $pesanProgress = "Magang belum dimulai.";
            } elseif ($magang->status == 'selesai' || ($hariIni->gt($tanggalSelesaiMagang) && $magang->status != 'belum')) {
                $progres       = 100;
                $pesanProgress = "Periode magang telah selesai.";
                if ($magang->status == 'sedang' && $hariIni->gt($tanggalSelesaiMagang)) {
                    // Otomatis update status ke selesai jika periode terlewati dan masih 'sedang'
                    // $magang->status = 'selesai';
                    // $magang->save();
                    // Atau biarkan industri yang mengubahnya manual
                }
            } elseif ($magang->status == 'sedang' || ($hariIni->gte($tanggalMulaiMagang) && $hariIni->lte($tanggalSelesaiMagang))) {
                if ($magang->status == 'belum' && $hariIni->gte($tanggalMulaiMagang)) {
                    // Jika status masih 'belum' tapi tanggal sudah masuk periode,
                    // bisa dipertimbangkan untuk otomatis update ke 'sedang' atau biarkan industri.
                    // Untuk saat ini, progress tetap dihitung.
                }
                $totalDurasi    = $tanggalMulaiMagang->diffInDays($tanggalSelesaiMagang);
                $durasiBerjalan = $tanggalMulaiMagang->diffInDays($hariIni);
                if ($totalDurasi > 0) {
                    $progres = ($durasiBerjalan / $totalDurasi) * 100;
                } else if ($totalDurasi == 0 && $hariIni->isSameDay($tanggalMulaiMagang)) {
                    $progres = 100;
                } else {
                    $progres = 0;
                }
                $progres       = round(min(100, max(0, $progres)));
                $pesanProgress = $progres . "% berjalan";
            } else if ($magang->status == 'belum') {
                $progres       = 0;
                $pesanProgress = "Magang belum dimulai atau menunggu tindakan.";
            }

        } else if ($magang->status == 'selesai') { // Jika pengajuan tidak ada tapi status magang sudah selesai
            $progres       = 100;
            $pesanProgress = "Magang telah selesai.";
        }

        $logHarian = LogHarianModel::with(['detail' => function ($query) {
            $query->orderBy('tanggal_kegiatan', 'desc')->orderBy('created_at', 'desc');
        }])
            ->where('mahasiswa_magang_id', $mahasiswa_magang_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(5);

        // Daftar status yang bisa dipilih industri, sesuai dengan enum di database
        $statusOptions = [
            'belum'   => 'Belum Mulai',
            'sedang'  => 'Sedang Berjalan',
            'selesai' => 'Selesai',
        ];

        return view('industri_page.magang.action', compact(
            'activeMenu',
            'userIndustri',
            'magang',
            'pengajuanDiterima',
            'progres',
            'pesanProgress',
            'tanggalMulaiMagang',
            'tanggalSelesaiMagang',
            'logHarian',
            'statusOptions' // Ganti $listStatusMagangIndustri dengan $statusOptions
        ));
    }

    public function updateStatus(Request $request, $mahasiswa_magang_id)
    {
        $validStatuses = ['belum', 'sedang', 'selesai']; // Status yang valid sesuai enum DB
        $validator     = Validator::make($request->all(), [
            'status_magang_baru' => 'required|string|in:' . implode(',', $validStatuses),
        ], [
            'status_magang_baru.in' => 'Status yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()->with('error', 'Gagal memperbarui status: ' . $validator->errors()->first());
        }

        $userIndustri = Auth::user();
        $industriId   = null; // Sesuaikan cara Anda mendapatkan industriId
        if (property_exists($userIndustri, 'industri_id')) {
            $industriId = $userIndustri->industri_id;
        } elseif (method_exists($userIndustri, 'getKey')) {
            $industriId = $userIndustri->getKey();
        }

        $magang = MagangModel::where('mahasiswa_magang_id', $mahasiswa_magang_id)
            ->whereHas('lowongan', function ($q) use ($industriId) {
                $q->where('industri_id', $industriId);
            })
            ->firstOrFail();

        $statusLama    = $magang->status;
        $statusRequest = $request->status_magang_baru;

        $magang->status = $statusRequest;
        $magang->save();

        /**
         * =============================================================
         * SINKRONISASI: Update jumlah alumni di tabel m_industri
         * =============================================================
         */
        // 1. Kondisi PENAMBAHAN alumni
        // Hanya jika status baru adalah 'selesai' DAN status lamanya BUKAN 'selesai'
        if ($statusRequest === 'selesai' && $statusLama !== 'selesai') {
            // Ambil industri dari relasi yang sudah di-load, lalu tambah 1
            $magang->lowongan->industri->increment('alumni_count');
        }
        // 2. Kondisi PENGURANGAN alumni (untuk koreksi jika terjadi kesalahan)
        // Hanya jika status lama adalah 'selesai' DAN status barunya BUKAN 'selesai'
        else if ($statusLama === 'selesai' && $statusRequest !== 'selesai') {
            // Ambil industri, dan kurangi 1.
            // Method decrement() aman dan tidak akan membuat angka menjadi minus.
            $magang->lowongan->industri->decrement('alumni_count');
        }

        // =============================================================
        // Akhir dari blok sinkronisasi
        // =============================================================

        // Ambil label status yang user-friendly
        $statusOptionsLabels = [
            'belum'   => 'Belum Mulai',
            'sedang'  => 'Sedang Berjalan',
            'selesai' => 'Selesai',
        ];
        $statusLabel = $statusOptionsLabels[$statusRequest] ?? ucfirst($statusRequest);

        return redirect()->route('industri.magang.action', $mahasiswa_magang_id)
            ->with('success', 'Status magang mahasiswa berhasil diperbarui menjadi "' . $statusLabel . '".');
    }

    public function approveLogHarian(Request $request, $logHarianDetail_id)
    {
        Log::info("Attempting to approve logHarianDetail_id: {$logHarianDetail_id}");

        $userIndustri = Auth::user(); // Ini SEHARUSNYA instance dari App\Models\IndustriModel

        // Validasi bahwa user yang login adalah instance dari IndustriModel
        if (! $userIndustri || ! ($userIndustri instanceof IndustriModel)) {
            Log::error("CRITICAL: Authenticated user is not an instance of IndustriModel as expected. Class: " . ($userIndustri ? get_class($userIndustri) : 'null') . ". Check auth guard configuration for industry routes.");
            return redirect()->back()->with('error', 'Sesi tidak valid atau otentikasi industri gagal.');
        }

                                                   // $userIndustri adalah instance IndustriModel, dapatkan ID-nya (primary key)
        $industriIdAuth = $userIndustri->getKey(); // Ini akan mengambil nilai dari 'industri_id'
        Log::info("Authenticated user is IndustriModel. Industri ID Auth: {$industriIdAuth}");

        if (! $industriIdAuth) {
            // Ini seharusnya tidak terjadi jika user terautentikasi dengan benar sebagai IndustriModel
            Log::error("Failed to determine industriIdAuth even though user is confirmed as IndustriModel instance. User PK: " . ($userIndustri ? $userIndustri->getKey() : 'N/A'));
            return redirect()->back()->with('error', 'Tidak dapat mengidentifikasi ID industri Anda.');
        }

        // Eager load dengan path relasi yang benar
        $logDetail = LogHarianDetailModel::with('logHarian.mahasiswaMagang.lowongan.industri')
            ->findOrFail($logHarianDetail_id);

        // Otorisasi: Pastikan log harian ini milik mahasiswa yang magang di industri yang login
        $industriPemilikLog = optional(optional(optional(optional($logDetail->logHarian)->mahasiswaMagang)->lowongan)->industri)->getKey();
        Log::info("Industri pemilik log (from logDetail): {$industriPemilikLog}");

        if ($industriPemilikLog != $industriIdAuth) {
            Log::warning("Authorization failed for approving log. Authenticated Industri ID: {$industriIdAuth}, Log's Industri ID: {$industriPemilikLog}");
            return redirect()->back()->with('error', 'Anda tidak berhak melakukan aksi ini karena log tidak terkait dengan industri Anda.');
        }

        $logDetail->status_approval_industri = 'Disetujui';
        $logDetail->catatan_industri         = $request->input('catatan_industri_approve_' . $logHarianDetail_id);
        $logDetail->save();

        Log::info("LogHarianDetail_id: {$logHarianDetail_id} approved successfully by Industri ID: {$industriIdAuth}");
        return redirect()->back()->with('success', 'Log harian berhasil disetujui.');
    }

    public function rejectLogHarian(Request $request, $logHarianDetail_id)
    {
        Log::info("Attempting to reject logHarianDetail_id: {$logHarianDetail_id}");
        $catatanFieldName = 'catatan_industri_reject_' . $logHarianDetail_id;

        $validator = Validator::make($request->all(), [
            $catatanFieldName => 'required|string|min:5',
        ], [
            $catatanFieldName . '.required' => 'Catatan penolakan wajib diisi.',
            $catatanFieldName . '.min'      => 'Catatan penolakan minimal 5 karakter.',
        ]);

        if ($validator->fails()) {
            Log::warning("Validation failed for rejecting logHarianDetail_id: {$logHarianDetail_id}", $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_reject_id_key', $logHarianDetail_id)
                ->with('error_reject_' . $logHarianDetail_id, $validator->errors()->first());
        }

        $userIndustri = Auth::user(); // Ini SEHARUSNYA instance dari App\Models\IndustriModel

        // Validasi bahwa user yang login adalah instance dari IndustriModel
        if (! $userIndustri || ! ($userIndustri instanceof IndustriModel)) {
            Log::error("CRITICAL: Authenticated user is not an instance of IndustriModel as expected. Class: " . ($userIndustri ? get_class($userIndustri) : 'null') . ". Check auth guard configuration for industry routes.");
            return redirect()->back()->with('error', 'Sesi tidak valid atau otentikasi industri gagal.');
        }

                                                   // $userIndustri adalah instance IndustriModel, dapatkan ID-nya (primary key)
        $industriIdAuth = $userIndustri->getKey(); // Ini akan mengambil nilai dari 'industri_id'
        Log::info("Authenticated user is IndustriModel. Industri ID Auth: {$industriIdAuth}");

        if (! $industriIdAuth) {
            // Ini seharusnya tidak terjadi jika user terautentikasi dengan benar sebagai IndustriModel
            Log::error("Failed to determine industriIdAuth even though user is confirmed as IndustriModel instance. User PK: " . ($userIndustri ? $userIndustri->getKey() : 'N/A'));
            return redirect()->back()->with('error', 'Tidak dapat mengidentifikasi ID industri Anda.');
        }

        // Eager load dengan path relasi yang benar
        $logDetail = LogHarianDetailModel::with('logHarian.mahasiswaMagang.lowongan.industri')
            ->findOrFail($logHarianDetail_id);

        // Otorisasi
        $industriPemilikLog = optional(optional(optional(optional($logDetail->logHarian)->mahasiswaMagang)->lowongan)->industri)->getKey();
        Log::info("Industri pemilik log (from logDetail): {$industriPemilikLog}");

        if ($industriPemilikLog != $industriIdAuth) {
            Log::warning("Authorization failed for rejecting log. Authenticated Industri ID: {$industriIdAuth}, Log's Industri ID: {$industriPemilikLog}");
            return redirect()->back()->with('error', 'Anda tidak berhak melakukan aksi ini karena log tidak terkait dengan industri Anda.');
        }

        $logDetail->status_approval_industri = 'Ditolak';
        $logDetail->catatan_industri         = $request->input($catatanFieldName);
        $logDetail->save();

        Log::info("LogHarianDetail_id: {$logHarianDetail_id} rejected successfully by Industri ID: {$industriIdAuth}");
        return redirect()->back()->with('success', 'Log harian berhasil ditolak.');
    }
    public function submitEvaluasi(Request $request, $mahasiswa_magang_id)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'feedback_industri' => 'required|string|min:20',
        ], [
            'feedback_industri.required' => 'Kolom evaluasi tidak boleh kosong.',
            'feedback_industri.min'      => 'Evaluasi harus berisi minimal 20 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('evaluasi_error', 'Gagal menyimpan evaluasi. Mohon periksa kembali input Anda.');
        }

        // 2. Otorisasi dan Cari Data Magang
        $userIndustri = Auth::user();
        $industriId   = $userIndustri->industri_id ?? $userIndustri->getKey();

        $magang = MagangModel::where('mahasiswa_magang_id', $mahasiswa_magang_id)
            ->whereHas('lowongan', function ($q) use ($industriId) {
                $q->where('industri_id', $industriId);
            })
            ->firstOrFail();

        if (! empty($magang->feedback_industri)) {
            return redirect()->back()
                ->with('evaluasi_error', 'Evaluasi untuk mahasiswa ini sudah diisi dan tidak dapat diubah lagi.');
        }

        // 3. Pastikan status magang sudah 'selesai'
        if (strtolower($magang->status) !== 'selesai') {
            return redirect()->back()
                ->with('evaluasi_error', 'Anda hanya dapat mengisi evaluasi jika status magang sudah "Selesai".');
        }

        // 4. Simpan data evaluasi
        $magang->feedback_industri = $request->feedback_industri;
        $magang->save();

        // 5. Redirect dengan pesan sukses
        return redirect()->route('industri.magang.action', $mahasiswa_magang_id)
            ->with('evaluasi_success', 'Evaluasi dari industri berhasil disimpan.');
    }
}
