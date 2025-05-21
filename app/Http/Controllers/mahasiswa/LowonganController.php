<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\KategoriSkillModel;
use App\Models\KotaModel;
use Illuminate\Http\Request;
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

                return '
            <div class="d-flex px-2 py-1">
                <div>
                    <img src="' . $logo . '" class="avatar avatar-sm me-3" alt="logo industri">
                </div>
                <div class="d-flex flex-column justify-content-center text-start">
                    <h6 class="mb-0 text-sm">' . $nama . '</h6>
                    <p class="text-xs text-secondary mb-0">' . $kota . '</p>
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

}
