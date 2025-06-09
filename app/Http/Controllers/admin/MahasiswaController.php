<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa  = MahasiswaModel::with('prodi')->get();
        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.index', compact('mahasiswa', 'activeMenu'));
    }

    public function list(Request $request)
    {
        // Ambil mahasiswa beserta relasi yang dibutuhkan untuk ditampilkan dan difilter
        $mahasiswaQuery = MahasiswaModel::with([
            'prodi',
            'dpa',                       // Relasi ke DosenModel untuk DPA
            'dosenPembimbing',           // Relasi ke DosenModel untuk Pembimbing Magang
            'magang',                    // Relasi ke MagangModel untuk cek status magang
        ])->select('m_mahasiswa.*'); // Selalu baik untuk select spesifik atau semua dari tabel utama

        return DataTables::of($mahasiswaQuery)
            ->addIndexColumn()
            ->addColumn('nama_lengkap_detail', function ($mahasiswa) {
                return view('admin_page.mahasiswa.partials.nama_detail', compact('mahasiswa'))->render();
            })
            ->addColumn('prodi_nama', fn($mahasiswa) => optional($mahasiswa->prodi)->nama_prodi ?? '-')
            ->addColumn('dpa_nama', fn($mahasiswa) => optional($mahasiswa->dpa)->nama_lengkap ?? '<span class="text-muted fst-italic">Belum Diatur</span>')
            ->addColumn('pembimbing_nama', fn($mahasiswa) => optional($mahasiswa->dosenPembimbing)->nama_lengkap ?? '<span class="text-muted fst-italic">Belum Ada</span>')
            ->addColumn('status_magang_display', function ($mahasiswa) {
                if ($mahasiswa->magang && in_array(strtolower($mahasiswa->magang->status), ['belum', 'sedang'])) {
                    return '<span class="badge bg-success">Sedang/Akan Magang</span>';
                } elseif ($mahasiswa->magang && strtolower($mahasiswa->magang->status) == 'selesai') {
                    return '<span class="badge bg-info">Magang Selesai</span>';
                }
                // Cek juga dari pengajuan jika belum masuk MagangModel tapi sudah diterima
                $pengajuanDiterima = $mahasiswa->pengajuan()
                    ->where('status', 'diterima') // Sesuai ENUM PengajuanModel
                    ->first();
                if ($pengajuanDiterima && ! $mahasiswa->magang) { // Diterima pengajuan tapi belum jadi magang record
                    return '<span class="badge bg-primary">Diterima (Menunggu Magang)</span>';
                }

                return '<span class="badge bg-secondary">Belum Magang</span>';
            })
            ->addColumn('aksi', function ($mahasiswa) {
                $btn = '<button onclick="modalAction(\'' . route('mahasiswa.verifikasi', $mahasiswa->mahasiswa_id) . '\')" class="btn btn-info btn-sm me-1 mb-1" title="Verifikasi Dokumen"><i class="fas fa-user-check"></i></button>';
                $btn .= '<button onclick="modalAction(\'' . route('mahasiswa.edit', $mahasiswa->mahasiswa_id) . '\')" class="btn btn-warning btn-sm me-1 mb-1" title="Edit Mahasiswa"><i class="fas fa-edit"></i></button>';
                $btn .= '<button onclick="modalAction(\'' . route('mahasiswa.deleteModal', $mahasiswa->mahasiswa_id) . '\')" class="btn btn-danger btn-sm mb-1" title="Hapus Mahasiswa"><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['aksi', 'dpa_nama', 'pembimbing_nama', 'status_magang_display', 'status_akun', 'nama_lengkap_detail'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $prodi = ProdiModel::all();
        $dosen = DosenModel::all();

        if ($request->ajax()) {
            return view('admin_page.mahasiswa.create', compact('prodi', 'dosen'));
        }

        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.create', compact('prodi', 'dosen', 'activeMenu'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_mahasiswa,email',
                'password'     => 'required|min:6',
                'ipk'          => 'nullable|numeric|min:0|max:4',
                'nim'          => 'required|unique:m_mahasiswa,nim',
                'status'       => 'required|boolean',
                'prodi_id'     => 'required',
                'dosen_id'     => 'nullable',
                'dpa_id'       => 'nullable',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            MahasiswaModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'password'     => bcrypt($request->password),
                'ipk'          => $request->ipk,
                'nim'          => $request->nim,
                'status'       => $request->status,
                'prodi_id'     => $request->prodi_id,
                'dosen_id'     => $request->dosen_id,
                'dosen_id'     => $request->dpa_id,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Mahasiswa berhasil ditambahkan',
            ]);
        }

        return redirect('/');
    }

    public function show(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'dosen', 'preferensiLokasi', 'skills'])->find($id);

        if ($request->ajax()) {
            return view('admin_page.mahasiswa.show', compact('mahasiswa'));
        }

        return redirect('/');
    }

    public function edit(Request $request, $id) // $id adalah mahasiswa_id
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'dpa', 'dosenPembimbing', 'magang'])->findOrFail($id);
        $prodiList = ProdiModel::orderBy('nama_prodi')->get(); // Ganti nama variabel agar tidak bentrok

        // Ambil dosen yang bisa jadi DPA (misal semua dosen atau dosen dengan role 'dpa')
        $dosenDpaList = DosenModel::where('role_dosen', 'dpa')->orderBy('nama_lengkap')->get();
        if ($dosenDpaList->isEmpty()) { // Fallback jika tidak ada DPA spesifik
            $dosenDpaList = DosenModel::orderBy('nama_lengkap')->get();
        }

        // Ambil dosen yang bisa jadi Pembimbing (misal dosen dengan role 'pembimbing')
        $dosenPembimbingList = DosenModel::where('role_dosen', 'pembimbing')->orderBy('nama_lengkap')->get();
        if ($dosenPembimbingList->isEmpty()) { // Fallback jika tidak ada pembimbing spesifik
            $dosenPembimbingList = DosenModel::orderBy('nama_lengkap')->get();
        }

        // Cek status magang mahasiswa
        $statusMagangMahasiswa = null;
        if ($mahasiswa->magang) {
            $statusMagangMahasiswa = $mahasiswa->magang->status; // e.g., 'belum', 'sedang', 'selesai'
        } else {
            // Cek juga dari tabel pengajuan jika statusnya 'diterima' tapi belum masuk magangModel
            $pengajuanDiterima = $mahasiswa->pengajuan()->where('status', 'diterima')->first();
            if ($pengajuanDiterima) {
                $statusMagangMahasiswa = 'akan_magang'; // Status custom untuk menandakan sudah diterima tapi belum di magangModel
            }
        }

        if ($request->ajax()) {
            return view('admin_page.mahasiswa.edit', compact(
                'mahasiswa',
                'prodiList',
                'dosenDpaList',
                'dosenPembimbingList',
                'statusMagangMahasiswa'
            ));
        }

        // Untuk non-AJAX (jika ada), meskipun biasanya modal edit via AJAX
        $activeMenu = 'mahasiswa';
        return view('admin_page.mahasiswa.edit', compact(
            'mahasiswa',
            'prodiList',
            'dosenDpaList',
            'dosenPembimbingList',
            'statusMagangMahasiswa',
            'activeMenu'
        ));
    }

    public function update(Request $request, $id)
    {
        // Gunakan findOrFail untuk keamanan, akan melempar error jika ID tidak ditemukan
        $mahasiswa = MahasiswaModel::findOrFail($id);

        // Amankan: Mahasiswa hanya boleh edit profilnya sendiri
        if (Auth::guard('mahasiswa')->check() && Auth::id() != $id) {
            Log::warning('Upaya akses tidak sah: Mahasiswa ' . Auth::id() . ' mencoba mengedit profil Mahasiswa ' . $id);
            return response()->json(['status' => false, 'message' => 'Anda tidak memiliki hak akses.'], 403);
        }

        // Siapkan variabel untuk aturan validasi dan data yang akan diupdate
        $rules       = [];
        $allowedData = [];

        if (Auth::guard('web')->check()) {
            // --- LOGIKA & VALIDASI UNTUK ADMIN ---
            Log::info('Request update mahasiswa dari ADMIN untuk ID: ' . $id);
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'email'        => 'required|email|unique:m_mahasiswa,email,' . $id . ',mahasiswa_id',
                'telepon'      => 'required|string|min:9|max:15',
                'nim'          => 'required|string|max:15|unique:m_mahasiswa,nim,' . $id . ',mahasiswa_id',
                'ipk'          => 'nullable|numeric|min:0|max:4.00',
                'status'       => 'required|boolean',
                'prodi_id'     => 'required|exists:m_prodi,prodi_id', // Nama tabel diperbaiki
                'dpa_id'       => 'nullable|exists:m_dosen,dosen_id',
                'dosen_id'     => 'nullable|exists:m_dosen,dosen_id',
                'password'     => 'nullable|string|min:6|max:20',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            // Admin boleh mengubah semua field ini
            $allowedData = ['nama_lengkap', 'email', 'telepon', 'nim', 'ipk', 'status', 'prodi_id', 'dpa_id', 'dosen_id'];

        } elseif (Auth::guard('mahasiswa')->check()) {
            // --- LOGIKA & VALIDASI UNTUK MAHASISWA (EDIT PROFIL) ---
            Log::info('Request update profil dari MAHASISWA ID: ' . $id);
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'email'        => 'required|email|unique:m_mahasiswa,email,' . $id . ',mahasiswa_id',
                'telepon'      => 'required|string|min:9|max:15',
                'password'     => 'nullable|string|min:6|max:20',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            // Mahasiswa hanya boleh mengubah field ini
            $allowedData = ['nama_lengkap', 'email', 'telepon'];
        }

        // Lakukan validasi berdasarkan aturan yang sudah ditentukan
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::warning('Validasi gagal untuk update Mahasiswa ID: ' . $id, $validator->errors()->toArray());
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa kembali data Anda.',
                'msgField' => $validator->errors()->toArray(),
            ], 422);
        }

        // Ambil hanya data yang diizinkan untuk diupdate
        $dataToUpdate = $request->only($allowedData);

        // Penanganan password (berlaku untuk admin dan mahasiswa)
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
            Log::info('Password sedang diupdate untuk Mahasiswa ID: ' . $id);
        }

        // Penanganan upload foto (berlaku untuk admin dan mahasiswa)
        if ($request->hasFile('foto')) {
            Log::info('Mendeteksi file foto untuk diunggah.');
            // Hapus foto lama jika ada
            if ($mahasiswa->foto && Storage::disk('public')->exists('mahasiswa/foto/' . $mahasiswa->foto)) {
                Storage::disk('public')->delete('mahasiswa/foto/' . $mahasiswa->foto);
                Log::info('Foto lama dihapus: ' . $mahasiswa->foto);
            }

            // Simpan foto baru dengan nama unik
            $path                 = $request->file('foto')->store('mahasiswa/foto', 'public');
            $dataToUpdate['foto'] = basename($path);
            Log::info('Foto baru berhasil diunggah: ' . $dataToUpdate['foto']);
        }

        // Lakukan update
        try {
            $mahasiswa->update($dataToUpdate);
            Log::info('Database berhasil diupdate untuk Mahasiswa ID: ' . $id, $dataToUpdate);
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Terjadi error saat update database untuk Mahasiswa ID: ' . $id, ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function deleteModal(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'dosen'])->find($id);
        return view('admin_page.mahasiswa.delete', compact('mahasiswa'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('mahasiswa.index');
        }

        $mahasiswa = MahasiswaModel::find($id);
        if ($mahasiswa) {
            $mahasiswa->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Mahasiswa berhasil dihapus',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Data mahasiswa tidak ditemukan',
        ]);
    }
    public function verifikasi(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'dpa', 'dosenPembimbing']) // Eager load relasi yang mungkin ditampilkan
            ->findOrFail($id);

        if ($request->ajax()) {
            // Untuk modal, kita hanya perlu $mahasiswa
            return view('admin_page.mahasiswa.verifikasi', compact('mahasiswa'));
        }

        $activeMenu = 'mahasiswa';

        return view('admin_page.mahasiswa.verifikasi', compact('mahasiswa'));
    }
    public function updateVerifikasi(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        $rules = [
            'status_verifikasi' => 'required|string|in:pending,valid,invalid',
            'alasan'            => 'required_if:status_verifikasi,invalid|nullable|string|max:1000',
            'skor_ais'          => 'nullable|integer|min:0|max:1000', // Sesuaikan max jika perlu
            'kasus'             => 'required|string|in:ada,tidak_ada',
            // Mahasiswa yang input organisasi dan lomba, admin hanya verifikasi profil secara umum
            // 'organisasi'        => 'required|string|in:aktif,sangat_aktif,tidak_ikut',
            // 'lomba'             => 'required|string|in:aktif,sangat_aktif,tidak_ikut',
        ];

        $messages = [
            'status_verifikasi.required' => 'Status verifikasi wajib dipilih.',
            'status_verifikasi.in'       => 'Status verifikasi tidak valid.',
            'alasan.required_if'         => 'Alasan penolakan wajib diisi jika status verifikasi adalah Invalid.',
            'skor_ais.integer'           => 'Skor AIS harus berupa angka.',
            'skor_ais.min'               => 'Skor AIS minimal 0.',
            'kasus.required'             => 'Status kasus mahasiswa wajib dipilih.',
            'kasus.in'                   => 'Status kasus tidak valid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $mahasiswa->status_verifikasi = $request->status_verifikasi;
            $mahasiswa->alasan            = $request->status_verifikasi === 'invalid' ? $request->alasan : null;
            $mahasiswa->skor_ais          = $request->input('skor_ais', $mahasiswa->skor_ais); // Jika tidak diisi, jangan ubah skor_ais yg ada
            $mahasiswa->kasus             = $request->kasus;

            // Kolom organisasi dan lomba diisi oleh mahasiswa, jadi tidak diupdate di sini oleh admin
            // kecuali jika memang ada kebutuhan admin untuk meng-override.
            // Jika admin bisa override, tambahkan ke $request->only() dan $fillable di model.
            // $mahasiswa->organisasi = $request->organisasi;
            // $mahasiswa->lomba = $request->lomba;

            $mahasiswa->save();

            return response()->json(['success' => true, 'message' => 'Status verifikasi dan data mahasiswa berhasil diperbarui.']);

        } catch (\Exception $e) {
            Log::error("Error updating verifikasi mahasiswa ID {$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}
