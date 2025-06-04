<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MagangModel;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $evaluasi = MagangModel::all();
        if (auth()->check()) {
            return redirect('/dashboard');
        }
        return view('landing.landing', compact('evaluasi'));
    }

    public function landing()
    {

        $industriesForMarquee = IndustriModel::inRandomOrder() // Ambil secara acak
                                    ->take(10) // Batasi jumlahnya agar tidak terlalu banyak
                                    ->get();
        if ($industriesForMarquee->count() > 0 && $industriesForMarquee->count() < 7) {
            $tempIndustries = collect();
            $repetitions = ceil(10 / $industriesForMarquee->count());
            for ($i = 0; $i < $repetitions; $i++) {
                $tempIndustries = $tempIndustries->merge($industriesForMarquee);
            }
            $industriesForMarquee = $tempIndustries->take(15); // Batasi total setelah duplikasi
        }

        $evaluasi = MagangModel::whereIn('mahasiswa_magang_id', [1, 2, 3])->get();
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return view('landing.landing', compact('evaluasi', 'industriesForMarquee'));
    }

    public function industri(Request $request)
    {
        $searchTerm = $request->input('search');
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
        $perPage = 12; // Jumlah item per halaman, bisa disesuaikan
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
            return view('dosen_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }
        if (Auth::guard('mahasiswa')->check()) {
            return view('mahasiswa_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }
        if (Auth::guard('industri')->check()) {
            return view('industri_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }

    }
}
