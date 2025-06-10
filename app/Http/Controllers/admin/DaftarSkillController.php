<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DetailSkillModel;
use App\Models\KategoriSkillModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DaftarSkillController extends Controller
{
    public function index()
    {
        $detailSkill = DetailSkillModel::all();
        $activeMenu = 'skill';
        return view('admin_page.skill.index', compact('detailSkill', 'activeMenu'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = DetailSkillModel::select('skill_id', 'skill_nama', 'kategori_skill_id');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('kategori', function ($item) {
                    return $item->kategori->kategori_nama ?? '-';
                })
                ->addColumn('aksi', function ($item) {
                    $btn  = '<button onclick="modalAction(\'' . url('/skill/' . $item->skill_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/skill/' . $item->skill_id . '/edit') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/skill/' . $item->skill_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function create(Request $request)
    {
        $kategori = KategoriSkillModel::all();
        $activeMenu = 'skill';

        return view('admin_page.skill.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'skill_nama' => 'required|string|max:255',
                'kategori_skill_id' => 'required|exists:m_kategori_skill,kategori_skill_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            DetailSkillModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Detail Skill berhasil ditambahkan'
            ]);
        }

        return redirect()->route('detail-skill.index');
    }

    public function show(Request $request, $id)
    {
        $detail = DetailSkillModel::with('kategori')->find($id);
        $activeMenu = 'skill';

        if ($request->ajax()) {
            return view('admin_page.skill.show', compact('detail'));
        }

        return view('admin_page.skill.show', compact('detail', 'activeMenu'));
    }

    public function edit(Request $request, $id)
    {
        $detail = DetailSkillModel::find($id);
        $kategori = KategoriSkillModel::all();
        $activeMenu = 'skill';

        if ($request->ajax()) {
            return view('admin_page.skill.edit', compact('detail', 'kategori'));
        }

        return view('admin_page.skill.edit', compact('detail', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $rules = [
                'skill_nama' => 'required|string|max:255',
                'kategori_skill_id' => 'required|exists:m_kategori_skill,kategori_skill_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $detail = DetailSkillModel::find($id);
            $detail->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Detail Skill berhasil diperbarui'
            ]);
        }

        return redirect()->route('detail-skill.index');
    }
    public function deleteModal(Request $request, $id)
    {
        $detail = DetailSkillModel::find($id);
        $activeMenu = 'skill';

        if ($request->ajax()) {
            return view('admin_page.skill.delete', compact('detail', 'activeMenu'));
        }

        return view('admin_page.skill.delete', compact('detail', 'activeMenu'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $detail = DetailSkillModel::find($id);
            $detail?->delete();

            return response()->json([
                'status' => true,
                'message' => 'Detail Skill berhasil dihapus.'
            ]);
        }

        return redirect()->route('detail-skill.index');
    }
}
