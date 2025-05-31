<?php
namespace App\Http\Controllers\Mahasiswa;

use Carbon\Carbon;
use App\Models\KotaModel;
use Illuminate\Http\Request;
use App\Models\KategoriSkillModel;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class LowonganController extends Controller
{
    public function index()
    {
        $listKota     = KotaModel::all();
        $listKategori = KategoriSkillModel::all();
        $activeMenu   = 'lowongan';

        return view('mahasiswa_page.lowongan.index', compact(
            'activeMenu',
            'listKota',
            'listKategori'
        ));
    }


    public function list(Request $request)
    {
        $query = DetailLowonganModel::with(['industri.kota', 'kategoriSkill'])
            ->select('m_detail_lowongan.*'); // Perhatikan perubahan di sini

        // Filter berdasarkan lokasi (kota)
        if ($request->has('lokasi') && $request->lokasi != '') {
            $query->whereHas('industri.kota', function ($q) use ($request) {
                $q->where('kota_id', $request->lokasi);
            });
        }

        // Filter berdasarkan jenis (kategori skill)
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('kategori_skill_id', $request->jenis);
        }

        // Pencarian global
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('judul_lowongan', 'like', "%{$search}%")
                    ->orWhereHas('industri', function ($q) use ($search) {
                        $q->where('industri_nama', 'like', "%{$search}%");
                    });
            });
        }

        return DataTables::of($query)
            ->addColumn('industri', function ($row) {
                $logo = $row->industri->logo
                ? asset('storage/logo_industri/' . $row->industri->logo)
                : asset('assets/default-industri.png');

                $nama = $row->industri->industri_nama ?? '-';
                $kota = $row->industri->kota->kota_nama ?? '-';
                $provinsi = $row->industri->kota->provinsi->provinsi_nama ?? '-';

                return '
            <div class="d-flex px-2 py-1">
                <div>
                    <img src="' . $logo . '" class="avatar avatar-sm me-3" alt="logo industri">
                </div>
                <div class="d-flex flex-column justify-content-center text-start">
                    <h6 class="mb-0 text-sm">' . $nama . '</h6>
                    <p class="text-xs text-secondary mb-0">' . $kota . ', ' . $provinsi .'</p>
                </div>
            </div>';
            })
            ->addColumn('jenis', fn($row) => $row->kategoriSkill->kategori_nama ?? '-')
            ->addColumn('judul', fn($row) => $row->judul_lowongan ?? '-')
            ->addColumn('slot', fn($row) => $row->slotTersedia())
            ->addColumn('periode', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') . ' - ' .
                \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y');
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('/mahasiswa/lowongan/' . $row->lowongan_id . '/show') . '\')" class="fw-bold text-success bg-transparent border-0 p-0">Detail</button>';
            })
            ->rawColumns(['industri', 'aksi'])
            ->make(true);
    }
    public function show($id)
    {
        $lowongan = DetailLowonganModel::with([
            'industri.kota',       // Untuk akses kota
            'kategoriSkill',       // Untuk akses kategori skill
            'lowonganSkill.skill', // Untuk akses nama skill
        ])->findOrFail($id);

        return view('mahasiswa_page.lowongan.show', compact('lowongan'));
    }

    public function getLowonganDetailJson(DetailLowonganModel $lowongan)
    {
        try {
            // Eager load relasi yang dibutuhkan untuk menghindari N+1 query
            $lowongan->load([
                'industri.kota.provinsi', // industri, lalu kota dari industri, lalu provinsi dari kota
                'kategoriSkill',
                'lowonganSkill.skill'     // lowonganSkill, dan skill dari setiap lowonganSkill
            ]);

            // Memproses skill yang dibutuhkan
            $required_skills = $lowongan->lowonganSkill->map(function ($lowonganSkill) {
                return [
                    'nama_skill'       => optional($lowonganSkill->skill)->skill_nama ?? 'N/A', // Menggunakan optional() untuk keamanan
                    'level_kompetensi' => $lowonganSkill->level_kompetensi ?? 'N/A',
                    // 'bobot'           => $lowonganSkill->bobot ?? 'N/A', // Uncomment jika Anda ingin mengirim bobot skill lowongan
                ];
            });

            // Mengambil data industri dan lokasi dengan aman
            $industri = $lowongan->industri;
            $kota = optional($industri)->kota;
            $provinsi = optional($kota)->provinsi;
            $lokasi_string = trim((optional($kota)->kota_nama ?? '') . ($kota && $provinsi ? ', ' . optional($provinsi)->provinsi_nama : ''), ', ');


            // Menyusun data untuk respons JSON
            $data = [
                'lowongan_id'           => $lowongan->lowongan_id,
                'judul_lowongan'        => $lowongan->judul_lowongan ?? 'Judul Tidak Tersedia',
                'industri_nama'         => optional($industri)->industri_nama ?? '-',
                'logo_industri_url'     => (optional($industri)->logo) ? asset('storage/logo_industri/' . $industri->logo) : asset('assets/default-industri.png'), // Pastikan path default benar
                'deskripsi_lengkap'     => nl2br(htmlspecialchars($lowongan->deskripsi ?? '')),
                'kategori_nama'         => optional($lowongan->kategoriSkill)->kategori_nama ?? 'Umum',

                'periode_magang'        => ($lowongan->tanggal_mulai && $lowongan->tanggal_selesai)
                                          ? (Carbon::parse($lowongan->tanggal_mulai)->isoFormat('D MMMM YYYY') . ' - ' . Carbon::parse($lowongan->tanggal_selesai)->isoFormat('D MMMM YYYY'))
                                          : 'Tidak ditentukan',
                // Menyediakan format tanggal Y-m-d untuk JavaScript (misal untuk date picker min/max)
                'periode_magang_raw'    => [
                    'start' => optional($lowongan->tanggal_mulai)->format('Y-m-d'),
                    'end'   => optional($lowongan->tanggal_selesai)->format('Y-m-d'),
                ],

                'periode_pendaftaran'   => ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                          ? (Carbon::parse($lowongan->pendaftaran_tanggal_mulai)->isoFormat('D MMMM YYYY') . ' s/d ' . Carbon::parse($lowongan->pendaftaran_tanggal_selesai)->isoFormat('D MMMM YYYY'))
                                          : 'Tidak ditentukan',

                // Pastikan accessor ini ada di DetailLowonganModel dan aman terhadap null
                'status_pendaftaran_text' => $lowongan->status_pendaftaran_text ?? 'Status Tidak Diketahui',
                'status_pendaftaran_badge_class' => $lowongan->status_pendaftaran_badge_class ?? 'bg-secondary',

                'lokasi'                => $lokasi_string ?: 'Lokasi Tidak Dicantumkan', // Tampilkan pesan jika lokasi kosong

                // Pastikan method slotTersedia() ada di DetailLowonganModel dan aman
                'slot_tersedia'         => method_exists($lowongan, 'slotTersedia') ? $lowongan->slotTersedia() : ($lowongan->slot ?? 0),
                'total_slot'            => $lowongan->slot ?? 0,
                'required_skills'       => $required_skills,
            ];

            return response()->json(['status' => true, 'data' => $data]);

        } catch (\Exception $e) {
            // Log error dengan lebih detail, termasuk stack trace
            Log::error("Error fetching lowongan detail JSON for ID {$lowongan->lowongan_id}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['status' => false, 'message' => 'Gagal memuat detail lowongan. Silakan coba lagi nanti.'], 500);
        }
    }
}
