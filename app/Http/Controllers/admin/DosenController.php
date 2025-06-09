<?php
namespace App\Http\Controllers\admin;

use App\Models\DosenModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index()
    {
        $dosen      = DosenModel::with('prodi')->get();
        $activeMenu = 'dosen';
        return view('admin_page.dosen.index', compact('dosen', 'activeMenu'));
    }
    public function list(Request $request)
    {
        $users = DosenModel::select(
            'dosen_id',
            'nama_lengkap',
            'email',
            'nip',
            'prodi_id',
        )
            ->with(['prodi']);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('prodi', function ($user) {
                return $user->prodi->nama_prodi ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $user->dosen_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create(Request $request)
    {
        $prodi = ProdiModel::all();
        if ($request->ajax()) {
            return view('admin_page.dosen.create', compact('prodi'));
        }
        $activeMenu = 'dosen';
        return view('admin_page.dosen.create', compact('prodi', 'activeMenu'));
    }
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required',
                'email'        => 'required|email|unique:m_dosen,email',
                'telepon'      => 'required|min:9|max:15',
                'password'     => 'required|min:6',
                'nip'          => 'required|unique:m_dosen,nip',
                'prodi_id'     => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            DosenModel::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'telepon'      => $request->telepon,
                'password'     => $request->password,
                'nip'          => $request->nip,
                'prodi_id'     => $request->prodi_id,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Dosen berhasil ditambahkan',
            ]);
        }

        return redirect('/');
    }

    public function show(Request $request, $id)
    {
        $dosen = DosenModel::with(['prodi'])->find($id);

        if ($request->ajax()) {
            return view('admin_page.dosen.show', compact('dosen'));
        }
    }

    public function edit(Request $request, $id)
    {
        $dosen = DosenModel::findOrFail($id);
        $prodi = ProdiModel::all();
        if ($request->ajax()) {
            return view('admin_page.dosen.edit', compact('dosen', 'prodi'));
        }
        $activeMenu = 'dosen';
        return view('admin_page.dosen.edit', compact('dosen', 'prodi', 'activeMenu'));
    }
    public function update(Request $request, $id)
    {
        // Log data yang masuk untuk debugging
        Log::info('Menerima request update untuk Dosen ID: ' . $id, $request->except('password', '_token'));

        // Cari dosen terlebih dahulu
        $dosen = DosenModel::find($id);
        if (! $dosen) {
            return response()->json(['status' => false, 'message' => 'Data dosen tidak ditemukan'], 404);
        }

        // Tentukan aturan validasi dan data yang diizinkan berdasarkan guard
        $rules       = [];
        $allowedData = [];

        if (Auth::guard('web')->check()) {
            // --- LOGIKA UNTUK ADMIN ---
            Log::info('Request diidentifikasi dari ADMIN (web guard)');
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'email'        => 'required|email|unique:m_dosen,email,' . $id . ',dosen_id',
                'telepon'      => 'nullable|string|min:9|max:15',
                'nip'          => 'required|string|unique:m_dosen,nip,' . $id . ',dosen_id',
                'prodi_id'     => 'required|exists:m_prodi,prodi_id',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            $allowedData = ['nama_lengkap', 'email', 'telepon', 'nip', 'prodi_id'];

        } else if (Auth::guard('dosen')->check() && Auth::id() == $id) {
            // --- LOGIKA UNTUK DOSEN (EDIT PROFIL SENDIRI) ---
            Log::info('Request diidentifikasi dari DOSEN (dosen guard) untuk profil sendiri');
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'telepon'      => 'nullable|string|min:9|max:15',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'password'     => 'nullable|string|min:6', // Password baru (opsional)
            ];
            // Dosen hanya boleh mengubah data ini
            $allowedData = ['nama_lengkap', 'telepon'];

        } else {
            // Jika bukan admin atau bukan dosen yang bersangkutan
            return response()->json(['status' => false, 'message' => 'Anda tidak memiliki hak akses untuk melakukan aksi ini.'], 403);
        }

        // Lakukan validasi
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa kembali data yang Anda masukkan.',
                'msgField' => $validator->errors(),
            ]);
        }

        // Ambil data yang sudah divalidasi dan diizinkan
        $data = $request->only($allowedData);

        // --- Penanganan Password ---
        // Untuk ADMIN yang mereset password
        if (Auth::guard('web')->check() && $request->input('reset_password') == "1") {
            $data['password'] = bcrypt($dosen->nip); // Reset ke NIP
            Log::info('Password direset oleh admin untuk Dosen ID: ' . $id);
        }
        // Untuk DOSEN yang mengubah password sendiri
        if (Auth::guard('dosen')->check() && $request->filled('password')) {
            $data['password'] = bcrypt($request->password);
            Log::info('Dosen ID: ' . $id . ' mengubah passwordnya sendiri.');
        }

        // --- Penanganan Upload Foto ---
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($dosen->foto && Storage::disk('public')->exists('dosen/foto/' . $dosen->foto)) {
                Storage::disk('public')->delete('dosen/foto/' . $dosen->foto);
                Log::info('Foto lama dihapus: ' . $dosen->foto);
            }

            // Simpan foto baru menggunakan metode storeAs yang lebih aman
            $path         = $request->file('foto')->storeAs('dosen/foto', $request->file('foto')->hashName(), 'public');
            $data['foto'] = basename($path);
            Log::info('Foto baru berhasil diunggah: ' . $data['foto']);
        }

        // Update data dosen di database
        try {
            $dosen->update($data);
            Log::info('Data Dosen ID: ' . $id . ' berhasil diperbarui di database.');
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal update database untuk Dosen ID: ' . $id, ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui data di database.',
            ], 500);
        }
    }

    public function deleteModal(Request $request, $id)
    {
        $dosen = DosenModel::with(['prodi'])->find($id);
        return view('admin_page.dosen.delete', compact('dosen'));
    }
    public function delete_ajax(Request $request, $id)
    {
        if (! $request->ajax()) {
            return redirect()->route('dosen.index');
        }
        $dosen = DosenModel::find($id);
        if ($dosen) {
            $dosen->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Dosen berhasil dihapus',
            ]);
        }
        return response()->json([
            'status'  => false,
            'message' => 'Data dosen tidak ditemukan',
        ]);
    }
}
