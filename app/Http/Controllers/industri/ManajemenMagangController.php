<?php
namespace App\Http\Controllers\industri;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\MagangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                if (in_array($statusLower, ['aktif', 'berjalan', 'diterima'])) {
                    $badgeBgClass = 'bg-success';
                } elseif (in_array($statusLower, ['selesai', 'lulus'])) {
                    $badgeBgClass = 'bg-primary';
                } elseif (in_array($statusLower, ['dalam penilaian', 'evaluasi'])) {
                    $badgeBgClass = 'bg-info';
                    // Untuk bg-info bawaan BS5, teks default (putih) biasanya sudah kontras.
                    // Jika tema Anda membuat bg-info terang, tambahkan: $textClass = 'text-dark';
                } elseif (in_array($statusLower, ['dibatalkan', 'tidak lulus', 'ditolak'])) {
                    $badgeBgClass = 'bg-danger';
                } elseif (in_array($statusLower, ['menunggu konfirmasi', 'pending'])) {
                    $badgeBgClass = 'bg-warning';
                    $textClass    = 'text-dark'; // Latar kuning biasanya butuh teks gelap agar terbaca
                } elseif ($status == 'Belum Ada Status') {
                    $badgeBgClass = 'bg-light';  // Latar terang
                    $textClass    = 'text-dark'; // Teks gelap agar terbaca
                }
                // Anda bisa menambahkan lebih banyak kondisi 'else if' sesuai variasi status Anda

                // Pengecekan tambahan jika tema Anda membuat bg-secondary terlalu terang
                // if ($badgeBgClass == 'bg-secondary') {
                //     $textClass = 'text-dark'; // Uncomment jika bg-secondary Anda terang
                // }

                return '<span class="badge ' . $badgeBgClass . ' ' . $textClass . '">' . ucfirst(htmlspecialchars($status)) . '</span>';
            })
            ->addColumn('aksi', function ($row) {
                $detailUrl   = '#'; // Contoh: route('industri.manajemen_magang.show', $row->mahasiswa_magang_id)
                $evaluasiUrl = '#'; // Contoh: route('industri.manajemen_magang.evaluasi', $row->mahasiswa_magang_id)

                $buttons = '<div class="btn-group" role="group">';
                $buttons .= '<a href="' . $detailUrl . '" class="btn btn-xs btn-outline-info" title="Lihat Detail"><i class="fas fa-eye"></i></a>';
                if ($row->status !== 'Selesai' && $row->status !== 'Lulus' && $row->status !== 'Dibatalkan') { // Contoh kondisi kapan tombol evaluasi muncul
                                                                                                                   // $buttons .= '<a href="' . $evaluasiUrl . '" class="btn btn-xs btn-outline-success ms-1" title="Beri/Update Evaluasi"><i class="fas fa-edit"></i></a>';
                }
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['mahasiswa_detail', 'status_magang', 'aksi'])
            ->make(true);
    }
}
