<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailLowonganModel;
use App\Models\KategoriSkillModel;
use App\Models\KotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LowonganController extends Controller
{
    public function index()
    {
        $listKota     = KotaModel::all();
        $listKategori = KategoriSkillModel::all();
        $activeMenu   = 'lowongan';

        // Query dasar dengan eager loading
        $query = DetailLowonganModel::with(['industri.kota', 'kategoriSkill']);

        // Filter berdasarkan lokasi (kota)
        if (request('lokasi')) {
            $query->whereHas('industri.kota', function ($q) {
                $q->where('kota_id', request('lokasi'));
            });
        }

        // Filter berdasarkan jenis (kategori skill)
        if (request('jenis')) {
            $query->where('kategori_skill_id', request('jenis'));
        }

        // Ambil data lowongan yang sudah difilter dan urutkan terbaru
        $lowongan = $query->latest()->get();

        return view('mahasiswa_page.lowongan.index', compact(
            'activeMenu',
            'lowongan',
            'listKota',
            'listKategori'
        ));
    }

    public function create()
    {
        $activeMenu = 'lowongan';
        return view('mahasiswa_page.lowongan.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_lowongan'  => 'required|string|max:255',
            'slot'            => 'required|integer|min:1',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DetailLowonganModel::create($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);

        if ($request->ajax()) {
            return view('mahasiswa_page.lowongan.edit', compact('lowongan'));
        }

        $activeMenu = 'lowongan';
        return view('mahasiswa_page.lowongan.edit', compact('lowongan', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul_lowongan'  => 'required|string|max:255',
            'slot'            => 'required|integer|min:1',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lowongan->update($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = DetailLowonganModel::find($id);
        if (! $lowongan) {
            return response()->json([
                'status'  => false,
                'message' => 'Lowongan tidak ditemukan.',
            ]);
        }

        $lowongan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lowongan berhasil dihapus.',
        ]);
    }

    public function deleteModal($id)
    {
        $lowongan = DetailLowonganModel::findOrFail($id);
        return view('mahasiswa_page.lowongan.delete', compact('lowongan'));
    }
}
