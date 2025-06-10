<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSkillModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriSkillController extends Controller
{
    public function index()
    {
        $activeMenu = 'kategori_skill';
        return view('admin_page.kategori_skill.index', compact('activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = KategoriSkillModel::select('kategori_skill_id', 'kategori_nama');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function ($item) {
                    $btn  = '<button onclick="modalAction(\'' . url('/kategori_skill/' . $item->kategori_skill_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kategori_skill/' . $item->kategori_skill_id . '/edit') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kategori_skill/' . $item->kategori_skill_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin_page.kategori_skill.create');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|string|max:255|unique:m_kategori_skill,kategori_nama',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KategoriSkillModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Kategori Skill berhasil ditambahkan'
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        $kategori = KategoriSkillModel::find($id);
        if ($request->ajax()) {
            return view('admin_page.kategori_skill.show', compact('kategori'));
        }
    }

    public function edit(Request $request, $id)
    {
        $kategori = KategoriSkillModel::find($id);
        if ($request->ajax()) {
            return view('admin_page.kategori_skill.edit', compact('kategori'));
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
                'kategori_nama' => 'required|string|max:255|unique:m_kategori_skill,kategori_nama,' . $id . ',kategori_skill_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $kategori = KategoriSkillModel::find($id);
            $kategori->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Kategori Skill berhasil diperbarui'
            ]);
        }
    }

    public function deleteModal(Request $request, $id)
    {
        $kategori = KategoriSkillModel::withCount('skills')->find($id);
        if ($request->ajax()) {
            return view('admin_page.kategori_skill.delete', compact('kategori'));
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $kategori = KategoriSkillModel::withCount('skills')->find($id);

            if ($kategori->skills_count > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus! Kategori ini masih digunakan oleh ' . $kategori->skills_count . ' skill.'
                ]);
            }

            $kategori?->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kategori Skill berhasil dihapus.'
            ]);
        }
    }
}
