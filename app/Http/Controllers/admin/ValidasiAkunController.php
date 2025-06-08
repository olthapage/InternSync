<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ValidasiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ValidasiAkunController extends Controller
{
    public function index()
    {
        $akun  = ValidasiAkun::get();
        $activeMenu = 'validasi_akun';
        return view('admin_page.validasi_akun.index', compact('akun', 'activeMenu'));
    }
    public function list(Request $request)
    {
        $query = ValidasiAkun::select('id', 'nama_lengkap', 'email', 'username', 'perkiraan_role', 'status_validasi');

        // Filter berdasarkan status_validasi atau perkiraan_role jika diberikan dari frontend
        if ($request->has('status_validasi')) {
            $query->where('status_validasi', $request->status_validasi);
        }

        if ($request->has('perkiraan_role')) {
            $query->where('perkiraan_role', $request->perkiraan_role);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('role', fn($akun) => ucfirst($akun->perkiraan_role ?? '-'))
            ->addColumn('status_validasi', function ($akun) {
                $status = strtolower($akun->status_validasi ?? 'pending');
                return match ($status) {
                    'approved' => '<span class="badge bg-success">Approved</span>',
                    'rejected' => '<span class="badge bg-danger">Rejected</span>',
                    default     => '<span class="badge bg-secondary">Pending</span>',
                };
            })

            ->addColumn('aksi', function ($akun) {
                $id = $akun->id;
                $btn = '<button onclick="modalAction(\'' . route('validasi-akun.verifikasi', $id) . '\')" class="btn btn-success btn-sm me-1 mb-1" title="Validasi Akun"><i class="fas fa-check-circle"></i></button>';
                $btn .= '<button onclick="modalAction(\'' . route('validasi.deleteModal', $id) . '\')" class="btn btn-danger btn-sm mb-1" title="Hapus"><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['status_validasi', 'aksi'])
            ->make(true);
    }
    public function verifikasi(Request $request, $id)
    {
        $akun = ValidasiAkun::findOrFail($id);

        if ($request->ajax()) {
            return view('admin_page.validasi_akun.verifikasi', compact('akun'));
        }

        $activeMenu = 'validasi_akun';
        return view('admin_page.validasi_akun.verifikasi', compact('akun'));
    }
    public function validasiAkun(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:pending,approved,rejected',
            'alasan' => 'required_if:status_validasi,rejected',
        ], [
            'status_validasi.required' => 'Status validasi harus dipilih.',
            'alasan.required_if' => 'Alasan wajib diisi jika menolak akun.',
        ]);

        $akun = ValidasiAkun::findOrFail($id);
        $status = $request->status_validasi;  // Pakai yang ini
        $emailTujuan = $akun->email;

        if ($status === 'approved') {
            // Pindahkan data ke tabel mahasiswa/dosen sesuai role yang ada di $akun
            if ($akun->perkiraan_role === 'mahasiswa') {
                MahasiswaModel::create([
                    'nama_lengkap' => $akun->nama_lengkap,
                    'email' => $akun->email,  // pakai email dari $akun
                    'password' => $akun->password,
                    'nim' => $akun->username,
                    'status' => 'aktif',
                    'level_id' => 2,
                ]);
            } elseif ($akun->perkiraan_role === 'dosen') {
                DosenModel::create([
                    'nama_lengkap' => $akun->nama_lengkap,
                    'email' => $akun->email,  // pakai email dari $akun
                    'password' => $akun->password,
                    'nip' => $akun->username,
                    'level_id' => 3,
                ]);
            }

            $akun->delete();

            Mail::raw("Akun Anda telah berhasil divalidasi dan sekarang aktif di sistem.", function ($message) use ($emailTujuan) {
                $message->to($emailTujuan)
                        ->subject('Validasi Akun Berhasil');
            });

            return response()->json([
                'status' => true,
                'message' => 'Akun berhasil divalidasi dan dipindahkan.'
            ]);
        }

        if ($status === 'rejected') {
            $akun->status_validasi = 'rejected';
            $akun->alasan_penolakan = $request->alasan;
            $akun->save();

            Mail::raw("Akun Anda ditolak dengan alasan berikut:\n\n" . $request->alasan, function ($message) use ($emailTujuan) {
                $message->to($emailTujuan)
                        ->subject('Validasi Akun Ditolak');
            });

            return response()->json([
                'status' => true,
                'message' => 'Akun ditolak dan tetap disimpan untuk ditinjau ulang.'
            ]);
        }

        // Untuk status 'pending' atau nilai lain yang tidak sesuai logika:
        return response()->json([
            'status' => false,
            'message' => 'Status verifikasi tidak valid.'
        ], 422);
    }

    public function deleteModal(Request $request, $id)
    {
        $akun = ValidasiAkun::find($id);
        return view('admin_page.validasi_akun.delete', compact('akun'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('mahasiswa.index');
        }

        $akun = ValidasiAkun::find($id);
        if ($akun) {
            $akun->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Akun berhasil dihapus',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Data akun tidak ditemukan',
        ]);
    }
}
