<?php
namespace App\Http\Controllers;

use App\Models\DetailLowonganModel;
use App\Models\IndustriModel;
use App\Models\LogHarianDetailModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaSkillModel;
use App\Models\PengajuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Redirect jika user sudah login
        if (auth()->check()) {
            return redirect('/dashboard');
        }

        // Ambil data yang sudah ada
        $evaluasi = MagangModel::all();

        // PERUBAHAN: Hitung jumlah mahasiswa dan industri
        $jumlahMahasiswa = MahasiswaModel::count();
        $jumlahIndustri = IndustriModel::count();

        // Kirim semua data ke view
        return view('landing.landing', compact('evaluasi', 'jumlahMahasiswa', 'jumlahIndustri'));
    }

    public function landing()
    {
        // === PERUBAHAN DI SINI ===
        // Menambahkan filter untuk hanya mengambil industri yang memiliki logo
        $industriesForMarquee = IndustriModel::whereNotNull('logo')
            ->where('logo', '!=', '') // Memastikan kolom logo tidak hanya spasi kosong
            ->inRandomOrder()        // Ambil secara acak
            ->take(5)               // Ambil 15 data untuk marquee
            ->get();

        // Logika untuk duplikasi jika data kurang untuk memenuhi marquee
        if ($industriesForMarquee->count() > 0 && $industriesForMarquee->count() < 15) {
            $tempIndustries = collect();
            // Hitung berapa kali perlu diulang untuk setidaknya mendapatkan 15 item
            $repetitions = ceil(15 / $industriesForMarquee->count());

            for ($i = 0; $i < $repetitions; $i++) {
                $tempIndustries = $tempIndustries->merge($industriesForMarquee);
            }
            // Ambil persis 15 item dari koleksi yang sudah diduplikasi
            $industriesForMarquee = $tempIndustries->take(15);
        }

        $jumlahMahasiswa = MahasiswaModel::count();
        $jumlahIndustri = IndustriModel::count();
        $evaluasi = MagangModel::whereIn('mahasiswa_magang_id', [1, 2, 3])->get();

        if (auth()->check()) {
            return redirect()->route('home');
        }

        return view('landing.landing', compact('evaluasi', 'industriesForMarquee', 'jumlahMahasiswa', 'jumlahIndustri'));
    }

    public function industri(Request $request)
    {
        $searchTerm  = $request->input('search');
        $currentPage = $request->input('page', 1); // Ambil nomor halaman, default ke 1

        // Mulai query dasar, eager load relasi jika ada dan dibutuhkan untuk performa
        // Asumsi model IndustriModel memiliki relasi bernama 'kota' dan 'kategori_industri'
        // Gantilah 'kota' dan 'kategori_industri' dengan nama relasi yang benar di model Anda
        $query = IndustriModel::query()->with(['kota', 'kategori_industri']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('industri_nama', 'like', '%' . $searchTerm . '%');

                // Contoh pencarian pada nama kategori industri melalui relasi
                // Pastikan relasi 'kategori_industri' ada di IndustriModel
                // dan tabel kategori_industris memiliki kolom 'kategori_nama'
                if (method_exists(IndustriModel::class, 'kategori_industri')) {
                    $q->orWhereHas('kategori_industri', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('kategori_nama', 'like', '%' . $searchTerm . '%'); // Sesuaikan 'kategori_nama'
                    });
                }

                // Contoh pencarian pada nama kota melalui relasi
                // Pastikan relasi 'kota' ada di IndustriModel
                // dan tabel kota memiliki kolom 'kota_nama'
                if (method_exists(IndustriModel::class, 'kota')) {
                    $q->orWhereHas('kota', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('kota_nama', 'like', '%' . $searchTerm . '%'); // Sesuaikan 'kota_nama'
                    });
                }
                // Anda bisa menambahkan kondisi orWhereHas lainnya jika perlu mencari di kolom lain dari relasi
            });
        }

        // Urutkan hasil
        $query->orderBy('industri_nama', 'asc');

                             // Paginasi hasil
        $perPage       = 12; // Jumlah item per halaman, bisa disesuaikan
        $allIndustries = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Tambahkan parameter pencarian ke link pagination
        if ($searchTerm) {
            $allIndustries->appends(['search' => $searchTerm]);
        }

        // Jika ini adalah permintaan AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Render view partial yang hanya berisi daftar industri dan pagination
            // Buat file 'partials.industry_list_ajax.blade.php'
            // yang berisi loop @foreach untuk $allIndustries dan $allIndustries->links()
            $html = view('landing.industri_list', compact('allIndustries', 'searchTerm'))->render();
            return response()->json(['html' => $html]);
        }

        // Jika bukan permintaan AJAX, tampilkan halaman penuh seperti biasa
        // 'landing_industri' adalah nama file Blade utama Anda
        return view('landing.landing_industri', compact('allIndustries', 'searchTerm'));
    }

    public function dashboard()
    {
        $mhsCount  = MahasiswaModel::count();
        $mhsMagang = MahasiswaModel::where('status', true)->count();
        $industri  = IndustriModel::count();
        $lowongan  = DetailLowonganModel::count();

        // Statistik Detail Magang dari MagangModel
        $mahasiswaSedangMagang     = MagangModel::where('status', 'sedang')->count();
        $mahasiswaSelesaiMagang    = MagangModel::where('status', 'selesai')->count();
        $mahasiswaBelumMulaiMagang = MagangModel::where('status', 'belum')->count(); // atau bisa dinamai AkanMagang

        // Statistik Pengajuan dari PengajuanModel
        // Asumsikan status di PengajuanModel adalah 'pending', 'diterima', 'ditolak'
        // Sesuaikan string status jika berbeda di model Anda
        $pengajuanMenunggu = PengajuanModel::where('status', 'belum')->count();
        $pengajuanDiterima = PengajuanModel::where('status', 'diterima')->count();
        $pengajuanDitolak  = PengajuanModel::where('status', 'ditolak')->count();

        // Statistik Distribusi Bidang Industri
        $distribusiIndustri = IndustriModel::join('m_kategori_industri', 'm_industri.kategori_industri_id', '=', 'm_kategori_industri.kategori_industri_id')
            ->select('m_kategori_industri.kategori_nama', DB::raw('count(m_industri.industri_id) as jumlah_industri'))
            ->groupBy('m_kategori_industri.kategori_industri_id', 'm_kategori_industri.kategori_nama') // Pastikan semua kolom non-agregat di groupBy
            ->orderBy('jumlah_industri', 'desc')
            ->get();

        // Menyiapkan data untuk Chart.js
        $labelsDistribusiIndustri = $distribusiIndustri->pluck('kategori_nama');
        $dataDistribusiIndustri   = $distribusiIndustri->pluck('jumlah_industri');

        $activeMenu = 'home';

        $activeMenu = 'home';
        if (Auth::guard('web')->check()) {
            return view('admin_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan', 'mahasiswaSedangMagang', 'mahasiswaSelesaiMagang',
                'mahasiswaBelumMulaiMagang',
                'pengajuanMenunggu',
                'pengajuanDiterima',
                'pengajuanDitolak',
                'distribusiIndustri',
                'labelsDistribusiIndustri',
                'dataDistribusiIndustri'));
        }
        if (Auth::guard('dosen')->check()) {
            $dosen     = Auth::user();
            $roleDosen = $dosen->role_dosen;

            if ($roleDosen == 'pembimbing') {
                // --- DATA UNTUK DOSEN PEMBIMBING ---

                // Ambil ID mahasiswa yang dibimbing oleh dosen ini
                $mahasiswaBimbinganIds = $dosen->mahasiswaBimbinganMagang()->pluck('mahasiswa_id');

                // 1. Kartu Statistik
                $totalBimbingan = $mahasiswaBimbinganIds->count();
                $sedangMagang   = MagangModel::whereIn('mahasiswa_id', $mahasiswaBimbinganIds)->where('status', 'sedang')->count();
                $selesaiMagang  = MagangModel::whereIn('mahasiswa_id', $mahasiswaBimbinganIds)->where('status', 'selesai')->count();


                                                                                                    // 2. Data untuk Tabel Mahasiswa Bimbingan (ambil 5 terbaru)
                $mahasiswaBimbinganList = MahasiswaModel::with('magang.lowongan.industri', 'prodi') // Eager load relasi
                    ->where('dosen_id', $dosen->dosen_id)                                               // Filter berdasarkan ID dosen yang login
                    ->latest()                                                                          // Ambil mahasiswa yang terbaru dibuat                                                                           // Batasi hanya 5 untuk ditampilkan di dashboard
                    ->get();

                // 3. Data untuk Timeline Aktivitas Terkini (5 log harian terbaru)
                $aktivitasTerkini = LogHarianDetailModel::where('status_approval_dosen', 'belum')
                    ->whereHas('logHarian.mahasiswaMagang', function ($query) use ($mahasiswaBimbinganIds) {
                        $query->whereIn('mahasiswa_id', $mahasiswaBimbinganIds);
                    })
                    ->with('logHarian.mahasiswaMagang.mahasiswa')
                    ->latest('created_at')->take(5)->get();

                                                           // 4. Data untuk Chart (contoh: progress dari 5 mahasiswa)
                                                           // Di aplikasi nyata, progress bisa dihitung dari jumlah logbook / total hari magang
                $chartMahasiswa = $mahasiswaBimbinganList; // Kita gunakan data yang sama untuk contoh
                $chartLabels    = $chartMahasiswa->pluck('nama_lengkap');
                $chartData      = $chartMahasiswa->map(function ($mhs) {
                    return rand(20, 100); // Progress acak untuk contoh
                });

                return view('dosen_page.dashboard', compact(
                    'activeMenu', 'dosen',
                    'totalBimbingan', 'sedangMagang', 'selesaiMagang',
                    'mahasiswaBimbinganList', 'aktivitasTerkini', 'chartLabels', 'chartData'
                ));

            } elseif ($roleDosen == 'dpa') {
                // --- LOGIKA BARU UNTUK DPA ---

                // Ambil ID mahasiswa perwalian yang statusnya sudah 'valid'
                $mahasiswaWaliIds = $dosen->mahasiswaWali()
                    ->where('status_verifikasi', 'valid') // Filter hanya mahasiswa terverifikasi
                    ->pluck('mahasiswa_id');

                // 1. Kartu Statistik
                $totalPerwalian        = $mahasiswaWaliIds->count();
                $skillMenungguValidasi = MahasiswaSkillModel::whereIn('mahasiswa_id', $mahasiswaWaliIds)
                    ->where('status_verifikasi', 'Pending')->count();

                // 2. Data untuk Tabel Mahasiswa Perwalian (ambil 5 dari yang sudah terverifikasi)
                $mahasiswaWaliList = $dosen->mahasiswaWali()
                    ->where('status_verifikasi', 'valid')
                    ->orderBy('nama_lengkap')->take(5)->get();

                // 3. Data untuk Timeline "Skill Terbaru Diunggah"
                // Mengambil 5 skill terbaru dari mahasiswa wali yang terverifikasi
                $skillTerbaru = MahasiswaSkillModel::whereIn('mahasiswa_id', $mahasiswaWaliIds)
                    ->with('mahasiswa', 'detailSkill')
                    ->latest('created_at') // Urutkan berdasarkan yang terbaru
                    ->take(5)
                    ->get();

                return view('dosen_page.dashboard', compact(
                    'activeMenu', 'dosen',
                    'totalPerwalian', 'skillMenungguValidasi',
                    'mahasiswaWaliList', 'skillTerbaru'
                ));
            }
        }
        if (Auth::guard('mahasiswa')->check()) {
            $mahasiswa        = MahasiswaModel::where('mahasiswa_id', Auth::id())->first();
            $jumlahSkill      = 0;
            $jumlahPortofolio = 0;
            if ($mahasiswa) {
                $jumlahPortofolio = $mahasiswa->portofolios()->count();
            }
            if ($mahasiswa) {
                $jumlahSkill = $mahasiswa->skills()->count();
            }

            $magang = Auth::user()->magang;

            $latestLogs = collect();

            // 2. Pastikan mahasiswa memiliki data magang
            if ($magang) {
                // 3. Ambil 3 log harian terakhir
                $latestLogs = $magang->logHarian()
                    ->with('detail')             // Eager loading untuk menghindari N+1 query problem
                    ->orderBy('tanggal', 'desc') // Urutkan berdasarkan tanggal terbaru
                    ->take(3)                    // Ambil hanya 3
                    ->get();
            }
            return view('mahasiswa_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan', 'jumlahSkill', 'jumlahPortofolio', 'latestLogs'));
        }
        if (Auth::guard('industri')->check()) {
            // Ambil data industri yang sedang login
            $industriUser = Auth::guard('industri')->user();
            $industriId   = $industriUser->industri_id;

            // 1. Hitung lowongan aktif milik industri ini
            $lowonganAktif = DetailLowonganModel::where('industri_id', $industriId)->count();

            // 2. Hitung total pelamar ke semua lowongan industri ini
            $totalPelamar = PengajuanModel::whereHas('lowongan', function ($query) use ($industriId) {
                $query->where('industri_id', $industriId);
            })->count();

            // 3. Hitung mahasiswa yang SEDANG magang di industri ini
            $mahasiswaMagangAktif = MagangModel::where('status', 'sedang')
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })->count();

            // 4. Hitung total alumni magang dari industri ini
            $totalAlumni = MagangModel::where('status', 'selesai')
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })->count();

            // 5. Statistik pengajuan untuk lowongan milik industri ini
            $pengajuanMenunggu = PengajuanModel::where('status', 'belum')
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })->count();

            $pengajuanDiterima = PengajuanModel::where('status', 'diterima')
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })->count();

            $pengajuanDitolak = PengajuanModel::where('status', 'ditolak')
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })->count();

            // 6. Ambil data pelamar terbaru (misal 5 terakhir) untuk ditampilkan
            $pelamarTerbaru = PengajuanModel::with(['mahasiswa', 'lowongan'])
                ->whereHas('lowongan', function ($query) use ($industriId) {
                    $query->where('industri_id', $industriId);
                })
                ->orderBy('created_at', 'desc') // Asumsi ada timestamp
                ->take(5)
                ->get();

            return view('industri_page.dashboard', compact(
                'activeMenu',
                'lowonganAktif',
                'totalPelamar',
                'mahasiswaMagangAktif',
                'totalAlumni',
                'pengajuanMenunggu',
                'pengajuanDiterima',
                'pengajuanDitolak',
                'pelamarTerbaru'
            ));
        }

    }
}
