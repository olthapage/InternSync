<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::with('prodi')->get();
        return view('mahasiswa.index', compact('mahasiswa'));
    }
}
