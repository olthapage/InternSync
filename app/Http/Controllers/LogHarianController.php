<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogHarianModel;
use App\Models\LogHarianDetailModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\MahasiswaPreferensiLokasiModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class LogHarianController extends Controller
{
    public function index()
    {
        return view('mahasiswa_page.logharian.index', [
            'activeMenu' => 'logharian'
        ]);
    }

    public function list(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $mahasiswaId = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');
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
            ->addColumn('aksi', function ($row) {
                $editUrl = route('logHarian.edit', ['id' => $row->logHarian_id]);
                $deleteUrl = route('logHarian.delete', ['id' => $row->logHarian_id]);
                $detailUrl = route('logHarian.show', ['id' => $row->logHarian_id]);

                return '
                <button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">Detail</button>
                <button class="btn btn-sm btn-warning" onclick="modalAction(\'' . $editUrl . '\')">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="modalAction(\'' . $deleteUrl . '\')">Hapus</button>';
            })
            ->editColumn('tanggal', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal));
            })
            ->rawColumns(['isi', 'aksi', 'status_approval_dosen'])
            ->make(true);
    }

    public function create()
    {
        $user = Auth::guard('mahasiswa')->user();
        $mahasiswaId = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');

        $lokasi = MahasiswaPreferensiLokasiModel::where('mahasiswa_id', $mahasiswaId)
            ->join('m_kota', 'user_preferensi_lokasi.kota_id', '=', 'm_kota.kota_id')
            ->orderBy('user_preferensi_lokasi.prioritas', 'asc')
            ->select('m_kota.kota_id as kota_id', 'm_kota.kota_nama')
            ->first();

        return view('mahasiswa_page.logharian.create', [
            'activeMenu' => 'logharian',
            'lokasi' => $lokasi,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $mahasiswaId = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');
        $mahasiswaMagangId = MagangModel::where('mahasiswa_id', $mahasiswaId)->value('mahasiswa_magang_id');

        $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|array|min:1',
            'aktivitas.*.deskripsi' => 'required|string',
            'aktivitas.*.lokasi' => 'required|string',
            'aktivitas.*.tanggal' => 'required|date',  
        ]);

        // Simpan log harian header (tanggal umum)
        $log = LogHarianModel::create([
            'mahasiswa_magang_id' => $mahasiswaMagangId,
            'tanggal' => $request->tanggal,
        ]);

        // Simpan detail aktivitas dengan tanggal masing-masing
        foreach ($request->aktivitas as $aktivitas) {
            LogHarianDetailModel::create([
                'logHarian_id' => $log->logHarian_id,
                'isi' => $aktivitas['deskripsi'],
                'lokasi' => $aktivitas['lokasi'],
                'tanggal_kegiatan' => $aktivitas['tanggal'],         
                'status_approval_dosen' => 'pending',
                'status_approval_industri' => 'pending',
                'catatan_dosen' => null,
                'catatan_industri' => null,
            ]);
        }

        return response()->json(['success' => 'Log harian berhasil disimpan.']);
    }


    public function edit($id)
    {
        $log = LogHarianModel::with('detail')->findOrFail($id);

        $user = Auth::guard('mahasiswa')->user();
        $mahasiswaId = MahasiswaModel::where('nim', $user->nim)->value('mahasiswa_id');

        $lokasi = MahasiswaPreferensiLokasiModel::where('mahasiswa_id', $mahasiswaId)
            ->join('m_kota', 'user_preferensi_lokasi.kota_id', '=', 'm_kota.kota_id')
            ->orderBy('user_preferensi_lokasi.prioritas', 'asc')
            ->select('m_kota.kota_id as kota_id', 'm_kota.kota_nama')
            ->first();

        return view('mahasiswa_page.logharian.edit', [
            'log' => $log,
            'activeMenu' => 'logharian',
            'lokasi' => $lokasi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|array|min:1',
            'aktivitas.*.deskripsi' => 'required|string',
            'aktivitas.*.lokasi' => 'required|string',
        ]);

        $log = LogHarianModel::findOrFail($id);
        $log->update([
            'tanggal' => $request->tanggal,
        ]);

        // Hapus semua detail lama
        LogHarianDetailModel::where('logHarian_id', $log->logHarian_id)->delete();

        // Buat ulang detail baru
        foreach ($request->aktivitas as $aktivitas) {
            LogHarianDetailModel::create([
                'logHarian_id' => $log->logHarian_id,
                'isi' => $aktivitas['deskripsi'],
                'lokasi' => $aktivitas['lokasi'],
                'status_approval_dosen' => 'pending', 
                'status_approval_industri' => 'pending',
                'catatan_dosen' => null,
                'catatan_industri' => null,
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
        if ($request->ajax()) {
            $log = LogHarianModel::find($id);

            if (!$log) {
                return response()->json([
                    'status' => false,
                    'message' => 'Log harian tidak ditemukan.'
                ]);
            }

            // Hapus relasi detail jika ada
            $log->detail()->delete();
            $log->delete();

            return response()->json([
                'status' => true,
                'message' => 'Log harian berhasil dihapus.'
            ]);
        }

        return redirect()->route('logHarian.index');
    }
    public function export_pdf()
{
    $user = auth()->user();

    $mahasiswa = MahasiswaModel::where('nim', $user->nim)->first();
    $dosen = DosenModel::find($mahasiswa->dosen_id);
    $lokasi = MahasiswaPreferensiLokasiModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)->first();
    $mahasiswaMagangId = MagangModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)->value('mahasiswa_magang_id');

    $logharian = LogHarianModel::where('mahasiswa_magang_id', $mahasiswaMagangId)
        ->orderBy('tanggal', 'asc')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mahasiswa_page.logharian.export_pdf', [
        'mahasiswa' => $mahasiswa,
        'dosen' => $dosen,
        'lokasi' => $lokasi,
        'lokasiMagang' => $lokasi->lokasi_nama ?? '---',
        'logharian' => $logharian,
    ]);

    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption("isRemoteEnabled", true);

    return $pdf->stream('Log Harian - ' . $mahasiswa->nama . ' - ' . now()->format('Ymd_His') . '.pdf');
}
}
